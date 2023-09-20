--- ARTISAN COMMANDS ---
- php artisan serve == starting server
- php artisan view:clear == Clearing "View" Cache
- php artisan make:controller Controllernamehere == creating controller
- php artisan migrate == runs all migrations (database stuff)
- php artisan migrate:fresh == drops every table in db and updates data !!! only used with empty tables !!!
- php artisan make:policy PostPolicy --model=Post == Policies in Laravel provide a way to centralize and manage authorization logic for specific models, allowing you to define fine-grained access control rules based on user permissions and actions. 
In Policies are happening all crud operations -> centralized
Policy have to be registered with laravel in provider folders AuthServiceProvider class.
-  php artisan storage:link == 
The php artisan storage:link command in Laravel creates a symbolic link between the public directory and the storage/app/public directory. This command allows you to access files stored in the storage/app/public directory directly from the public web directory of your Laravel application.
- composer require intervention/image == composer require intervention/image downloads and installs Intervention Image package and its dependencies. It enables efficient image manipulation in PHP applications
- php artisan db:seed == This db:seed command will simply add new items to your tables but will not edit or delete any of your existing data. 
- php artisan migrate:fresh --seed == !!! It will completely erase all of your tables and data, then use your migration files to re-build all the tables, and finally use our seed data to populate the tables.
- composer require laravel/scout == search laravel search packet solution(Laravel Scout package is downloaded from the Packagist repository). 
 *** php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider" == for using scout. alternatives(a == lgolia, mili search ...)
 - npm run dev == performs tasks such as compiling and bundling JavaScript, CSS, and other assets, optimizing code, and setting up a development environment. 
 - npm run build == in public folder makes folder build where minified css js are 
 - npm install dompurify == for serach console js
 - php artisan event:generate == creates classes for event and listener and is global for whole app and reusable
 - composer require pusher/pusher-php-server == pusher package installation with composer
 - php artisan make:event ChatMessage == creates event
- npm install laravel-echo pusher-js == By installing Laravel Echo and Pusher.js, you'll have the necessary tools to enable real-time communication and broadcasting features in your Laravel application using Pusher as the broadcasting driver.
- php artisan make:mail NewPostEmail == creates in app folder new Folder Email and in it Email class with all stuff
- php artisan make:job SendNewPostEmail == This job class can then be customized to define the logic for sending an email when a new post is created.
- php artisan queue:table == created new table to db
- schedule docu == https://laravel.com/docs/10.x/scheduling 
- remember() == Using the remember() method can significantly improve performance by avoiding redundant operations or expensive database queries. It allows you to leverage caching to speed up your application and reduce the load on your database or external services.


<!-- - -- -- -- --- - -- - -- - - -  - - - - - --- - - - - - --->

--- model ---
- responsible for crud operations  and how to perform
- defining relationtips between e.g. posts and users etc.
- foreignId() is a Laravel schema builder method used to define a foreign key column.
- constrained() is a method that sets up the foreign key constraint by referencing the primary key of the referenced table.
- onDelete('cascade') is an option to specify the cascading delete behavior for the foreign key constraint.

<!-- - -- -- -- --- - -- - -- - - -  - - - - - --- - - - - - --->

--- view ---

<!-- - -- -- -- --- - -- - -- - - -  - - - - - --- - - - - - --->

--- controller --
- auth()->attempt([...]) is a method in Laravel used for authentication. It attempts to log in a user by checking the provided credentials against the user records in the database.
- middleware in Laravel provides a flexible and modular way to process and filter HTTP requests and responses, allowing you to add additional functionality and control to your application's routing system.
- type-hinting the $post parameter as Post, you are specifying the expected type for that parameter, indicating that it should be an instance of the Post class or its subclasses.
- Laravel -->Gate<-- ==
- broadcasting == Broadcasting in Laravel simplifies the process of implementing real-time functionality in your application, making it easier to build features like chat systems, notifications, live updates, and more. https://laravel.com/docs/10.x/broadcasting
- pusher allows realtime connection 
- 


<!-- - -- -- -- --- - -- - -- - - -  - - - - - --- - - - - - --->

--- Laravel's Blade ---
- @csrf is a Blade directive used for Cross-Site Request Forgery (CSRF) protection. Including @csrf in your forms is an important security practice to protect your application from CSRF attacks.
- @error() directive in Laravel simplifies the process of displaying validation error messages within your Blade templates, helping you provide meaningful feedback to users when form validation fails.
- value="{{ old('username') }}" is a convenient way to populate an input field with the previously submitted value, helping users see and modify their input after validation errors occur.
- {{ $slot }} provides a way to include dynamic content within a component or layout, allowing for flexible and reusable templates in Laravel.
- auth()->user()->username is used to retrieve the username of the currently authenticated user in Laravel. It allows you to access and use the username for various purposes within your application.
- @auth --> The @auth directive provides a convenient way to conditionally show content based on the authentication status, making it easier to handle different views and actions depending on whether the user is logged in or not.
-  @unless() --> If the provided condition evaluates to false, the content within the @unless directive will be rendered.
If the condition evaluates to true, the content will be skipped and not rendered.
- 

<!-- - -- -- -- --- - -- - -- - - -  - - - - - --- - - - - - --->
--- ---
- input name="password_confirmation" == ->confirmation<- validation method 

--- namespace ---
-namespaces in PHP provide a way to organize and separate code elements, helping to avoid naming conflicts and providing a
structured approach to code organization and management.



<!-- - -- -- -- --- - -- - -- - - -  - - - - - --- - - - - - --->

--- HTTP status codes ---
- 200 OK: Indicates a successful request and the server has returned the requested resource.
- 201 Created: Indicates a successful request that resulted in the creation of a new resource.
- 204 No Content: Indicates a successful request, but there is no content to return in the response body.
- 301 Moved Permanently: Indicates that the requested resource has been permanently moved to a new location.
- 400 Bad Request: Indicates that the server cannot understand the request due to malformed syntax or invalid parameters.
- 401 Unauthorized: Indicates that authentication is required to access the requested resource, and the client has not provided valid credentials.
- 403 Forbidden: Indicates that the server understands the request but refuses to authorize access to the resource.
- 404 Not Found: Indicates that the requested resource could not be found on the server.
- 500 Internal Server Error: Indicates an unexpected error occurred on the server that prevented it from fulfilling the request.
- 503 Service Unavailable: Indicates that the server is temporarily unable to handle the request due to maintenance or overload.