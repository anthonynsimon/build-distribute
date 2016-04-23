<?php

namespace App\Helpers;

use AWS;
use App\Build;

class AwsS3Service
{
	public static function getPresignedLink($folder, $file, $minutes = 60)
	{
		try {
			$s3 = AWS::createClient('s3');

			$cmd = $s3->getCommand('GetObject', [
				'Bucket' => $folder,
				'Key'    => $file
			]);

			$request = $s3->createPresignedRequest($cmd, '+'.$minutes.' minutes');
			$presignedUrl = (string)$request->getUri();
		}
		catch(\Exception $e) {
			abort(500);
		}
		
		return $presignedUrl;
	}
	
	public static function uploadObjectWithBody($folder, $file, $body)
	{		
		try {
			$s3 = AWS::createClient('s3');

			$result = $s3->putObject([
				'Bucket' => $folder,
				'Key'    => $file,
				'Body' => $body
			]);
		}
		catch(\Exception $e) {
			abort(500);
		}
				
        return $result;
	}

	public static function uploadFileWithPath($folder, $file, $pathToFile)
	{		
		try {
			$s3 = AWS::createClient('s3');

			$result = $s3->putObject([
				'Bucket' => $folder,
				'Key'    => $file,
				'SourceFile' => $pathToFile
			]);

			$client->waitUntil('ObjectExists', array(
			    'Bucket' => $folder,
			    'Key'    =>  $file
			));
		}
		catch(\Exception $e) {
			abort(500);
		}
				
        return $result;
	}

	public static function listBuckets() {
		$s3 = AWS::createClient('s3');

		$result = $s3->listBuckets();

		$buckets = [];
		foreach ($result['Buckets'] as $bucket) {
    		$buckets[] = $bucket['Name'];
		}

		return $buckets;

	}

	public static function listObjectsInBucket($bucketName) {
		$s3 = AWS::createClient('s3');

		$iterator = $s3->getIterator('ListObjects', array(
		    'Bucket' => $bucketName
		));

		$objects = [];
		foreach ($iterator as $object) {
		    $objects[] =  $object['Key'];
		}

		return $objects;
	}
}
