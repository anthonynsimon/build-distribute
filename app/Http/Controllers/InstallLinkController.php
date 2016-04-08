<?php

namespace App\Http\Controllers;

use Config;
use Hash;
use Response;
use App\Helpers\AwsS3Service;
use App\Build;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;

class InstallLinkController extends Controller
{

	public function getAwsPlist (Request $request, $buildId, $token)
	{
		$urldecoded = str_replace("+", "/", $token);
		$urldecoded = str_replace("-", "\\", $urldecoded);
		$valid = Hash::check(Config::get('app.key'), $urldecoded);
				
		if (!$valid) {
			abort(403);
		}
		
		$build = Build::find($buildId);
		
		if (!$build) {
			abort(404);
		}
		
		// TODO: store link and only generate new one if already expired
		
		// Generates plist for iOS devices and uploads to S3
		$this->generateAndUploadPlist($build);
		
		// Activate link in S3 to plist file, also for 60 minutes
		$link = AwsS3Service::getPresignedLink($build->installFolder, $build->installFileName.'.plist', 60);
		
		// Redirect iOS to the plist in S3
		return redirect()->intended($link);
	}
	
	public function getAwsBuild ($buildId)
	{
		$build = Build::find($buildId);
		
		if (!$build) {
			abort(404);
		}
		
		// Defaults to 60 minutes
		$link = AwsS3Service::getPresignedLink($build->installFolder, $build->installFileName);
		
		return redirect()->intended($link);
	}
	
	// iOS wants the plist and the ipa in the same domain under HTTPS
	function generateAndUploadPlist ($build)
	{		
		// Activate Aws Build link for 60 minutes
		$data = [
			'{%url%}' => AwsS3Service::getPresignedLink($build->installFolder, $build->installFileName, 60),
			'{%bundleIdentifier%}' => $build->bundleIdentifier,
			'{%bundleVersion%}' => $build->iphoneBundleVersion,
			'{%iphoneTitle%}' => $build->iphoneTitle
		];
		
		// Prepare plist
		$contents = $this->fillPlist($data);
		
		// Upload to S3, overwrite old plist
		$s3Object = AwsS3Service::uploadObjectWithBody($build->installFolder, $build->installFileName.'.plist', $contents);
		
		return $s3Object;
	}
	
	function fillPlist($data) {
		// Using Laravels template system didn't work.
		// TODO: make this nicer :)
		$contents = "<?xml version='1.0' encoding='UTF-8'?>
					<!DOCTYPE plist PUBLIC ' -//Apple//DTD PLIST 1.0//EN' 'http://www.apple.com/DTDs/PropertyList-1.0.dtd'>
					<plist version='1.0'>
					<dict>
						<key>items</key>
						<array>
							<dict>
								<key>assets</key>
								<array>
									<dict>
										<key>kind</key>
										<string>software-package</string>
										<key>url</key>
										<string><![CDATA[{%url%}]]></string>
									</dict>
								</array>
								<key>metadata</key>
								<dict>
									<key>bundle-identifier</key>
									<string>{%bundleIdentifier%}</string>
									<key>bundle-version</key>
									<string>{%bundleVersion%}</string>
									<key>kind</key>
									<string>software</string>
									<key>title</key>
									<string>{%iphoneTitle%}</string>
								</dict>
							</dict>
						</array>
					</dict>
					</plist>";

		foreach ($data as $key => $value) {
			$contents = str_replace($key, $value, $contents);
		}
		
		return $contents;
	}
}
