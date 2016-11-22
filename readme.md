# Wolpertinger Build Distribution

iOS/Android build distribution service and web client at Wolpertinger Games.  
Live at: <https://builds.wolpertingergames.com>

**Main use case:**  
- Manage user's access/roles for projects and builds. New users have no projects assigned to them initially, they require an admin to authorize it for them.
- Keep track of each build's info and location as the Continuous Integration System pushes them.
- Can generate a build's Plist and a pre-signed link for direct download from Amazon S3. On Android it just generates the pre-signed link as no Plist is required.


## Dotenv file
Sample .env file for server configuration:
```
APP_ENV=production
APP_DEBUG=false
APP_KEY=YOUR_APP_KEY
APP_URL=https:/domain.com

AWS_KEY=YOUR_AMAZON_KEY
AWS_SECRET=YOUR_AMAZON_SECRET_CODE

JWT_SECRET=YOUR_JWT_SECRET_CODE

GOOGLE_ID=YOUR_GOOGLE_ID
GOOGLE_SECRET=YOUR_SUPER_SECRET
GOOGLE_REDIRECT=https://domain.com/oauth2/handle/google
```


## API Reference
### Authentication
Only users with the role of *superAdmin* have access to the back-end API. To generate a JWT you must post the user's email and password as a JSON to:
```
/auth/authenticate
```
After that, include your token as an Authorization header for every request using the format: 'Bearer YOUR_JWT_TOKEN'
```
'Bearer YOUR_JWT_TOKEN'
```
You can query your data by sending a GET request with the JWT attached to the following route:
```
/auth/me
```

### API v1 routes
**Base route for API:**
```
/api/v1
```

**Builds:**
```
GET /builds => returns all builds (accepts query string key, value pairs: 'platform', 'orderBy' and 'orderType')
GET /builds/{ID} => returns build by ID
PUT /builds/{ID} => Updates a build and returns with changes
DELETE /builds/{ID} => Deletes a build and returns last details
POST /builds => stores a new build and returns if successful

Fields: 
{
  'projectIdent' => required | ident for the project that it belongs to
  'bundleIdentifier' => required
  'installFolder' => required | Amazon S3 install folder
  'installFileName' => required  | Amazon S3 install file name
  'version' => 'required
  'buildNumber' => required | Must be unique for the project it's associated with
  'platform' => required | normally iPhone or android (camelCase)
  'revision' => required
  'tag' => optional
  'androidBundleVersionCode' => optional
  'iphoneBundleVersion' => optional | will be inserted into the Plist
  'iphoneTitle' => optional | will be inserted into the Plist
}
```

**Projects:**
```
GET /projects => returns all projects
GET /projects/{ID} => returns project by ID
PUT /projects/{ID} => Updates a project and returns with changes
DELETE /projects/{ID} => Deletes a project and returns last details
POST /projects => stores a new project and returns if successful

Fields: 
{
  'name' => required | display name for the project
  'ident' => required | will be used to identify the project when posting new builds
}
```

**Projects <-> Builds:**
```
GET /projects/{PROJECT_ID}/builds => returns all builds for this project
GET /projects/{PROJECT_ID}/builds/{BUILD_ID} => returns a nested build
GET /projects/{ID}/head?platform={PLATFORM} => returns head build for the platform specified
GET /builds/{BUILD_ID}/project => returns the project to which the build belongs to
```

**Users:**
```
GET /users => returns all users
GET /users/{ID} => returns a specific user

NOTE: Creating, updating and deleting users over the API is disabled by default.
Users should only register via the web client as there is currently no use-case for an API end point.
```

### DB seed
By default no DB seed is provided. A sample seed:  
**Users**
```
DB::table('users')->insert([
  'name' => 'admin',
  'email' => 'admin@admin.com',
  'password' => bcrypt('AWESOME_PASSWORD'),
  'role_id' => '1',
]);
```

**Roles**
```
DB::table('roles')->insert([
  'name' => 'superAdmin',
  'label' => 'Super Admin',
  'description' => 'Has all rights and permissions.'
]);
```

**Projects**
```
DB::table('projects')->insert([
	'name' => 'My Project',
	'ident' => 'myProject'
]);
```

**Builds**
```
DB::table('builds')->insert([
	"project_id" => "1",
	"bundleIdentifier" => "com.example.myproject",
	"installFolder" => "exampleFolder",
	"installFileName" => "exampleFileName",
	"version" => "1.0",
	"buildNumber" => "25",
	"platform" => "iPhone",
	"revision" => "138",
	"tag" => null,
	"androidBundleVersionCode" => null,
	"iphoneBundleVersion" => "1.0",
	"iphoneTitle" => "PixelBunker"
]);
```
