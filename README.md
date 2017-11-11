# formkit
A nice bit of kit to help manage form-submissions in Laravel

## Introduction
FormKit is a two-part package:
- FormKit\SubmissionKit dramatically streamlines the work you have to do on the back-end to manage data-submissions and forms
- FormKit\TemplateKit dramatically reduces the amount of front-end templating you have to do to build nice-looking forms using Blade & Bootstrap
- Together, a developer can get rapidly build out comprehensive solutions to manage all different kinds of data, then tweak on an as-needed basis

## Installation
Recommended installation is via [Composer](https://getcomposer.org):

```composer require thehiredgun/formkit```

or, add this to your composer.json:

```
"require": {
    ...,
    "thehiredgun/formkit": "^1.0",
    ...
}
```

## SubmissionKit Quick-Start
### For traditional Web Application use:
We're going to use a single(!) controller method to manage getting a form and handling a form-submission:
In routes/web.php:
```php
Route::match(['get', 'post'], '/books/{book}/edit', 'BookController@form');
Route::match(['get', 'post'], '/books/add', 'BookController@form');
```
Then, in app/Http/Controllers/BookController.php:
```php
...
use App\Models\Eloquent\Book;
use Illuminate\Http\Request;
use TheHiredGun\FormKit\SubmissionKit\SubmissionKit;
...
    public function form(Request $request, Book $book = null)
    {
        // define your rules for the form.
        // multi-dimensional array is the preferred style, but you can use an array of strings, as well
        $rules = [
            'title' => [
                'required',
                'string',
            ],
            'author' => [
                'required',
                'string',
            ],
            'published_on' => [
                'required',
                'date_format:Y-m-d',
            ],
        ];
        $form = new SubmissionKit($request, $rules);
        // if the form has been submitted
        if ($request->isMethod('post')) {
            $form->validate();              // validate the form
            $form->setProperties($book);    // set the properties on the $book where the values are valid
            if ($form->isValid()) {
                $book->save();

                return redirect()->route('books');
            }
        }

        return view('books.form', [
            'book'   => $book,
            'errors' => $submissionKit->getErrors(),
        ]);
    }
```

### SubmissionKit For RESTful API usage:
Here we'll use the SubmissionKit in two separate methods: one for POST, and one for PUT
In routes/api.php:
```php
Route::post('/books', 'BookController@post');
Route::put('/books/{book}', 'BookController@put');
```
And the BookController:
```php
...
    public function post(Request $request)
    {
        $rules = [
            'title' => [
                'required',
                'string',
            ],
            'author' => [
                'required',
                'string',
            ],
            'published_on' => [
                'required',
                'date_format:Y-m-d',
            ],
        ];
        $form = new SubmissionKit($request, $rules);
        $form->validate();              // validate the form
        $form->setProperties(new Book());    // set the properties on the $book where the values are valid
        if ($form->isValid()) {
            $book->save();

            return response($book, 201);
        }

        return response([
            'errors' => $submissionKit->getErrors(),
        ], 400);
    }

    public function put(Request $request, Book $book)
    {
        $rules = [
            'title' => [
                'required',
                'string',
            ],
            'author' => [
                'required',
                'string',
            ],
            'published_on' => [
                'required',
                'date_format:Y-m-d',
            ],
        ];
        $form = new SubmissionKit($request, $rules);
        $form->validate();              // validate the form
        $form->setProperties($book);    // set the properties on the $book where the values are valid
        if ($form->isValid()) {
            $book->save();

            return response(200);
        }

        return response([
            'errors' => $submissionKit->getErrors(),
        ], 400);
    }
```

