<?php

use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use App\Services\PdfGeneratorService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');

    $this->admin = User::factory()->create(['is_admin' => true]);
    $this->customer = User::factory()->create();

    $this->order = Order::create([
        'user_id' => $this->customer->id,
        'order_number' => 'TEST-'.time(),
        'customer_name' => $this->customer->name,
        'email' => $this->customer->email,
        'status' => 'pending',
        'payment_status' => 'paid',
        'total_amount' => 100,
    ]);
});

test('admin can upload docx file and it is stored as pdf', function () {
    $this->actingAs($this->admin);

    $bookData = [
        'title' => 'Test Book',
        'author' => 'Test Author',
        'description' => 'Test Description',
        'price' => 10.00,
        'category' => 'Test',
        'isbn' => '123456789',
        'pages' => 100,
        'published_year' => 2024,
        'stock' => 10,
        'is_featured' => false,
        'is_free' => false,
    ];

    $response = $this->post(route('admin.books.store'), array_merge($bookData, [
        'cover_image' => UploadedFile::fake()->image('cover.jpg'),
        'book_pdf' => UploadedFile::fake()->create('test.docx', 1024, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
    ]));

    $response->assertRedirect();
    $this->assertDatabaseHas('books', ['title' => 'Test Book']);
});

test('admin can upload doc file and it is stored as pdf', function () {
    $this->actingAs($this->admin);

    $bookData = [
        'title' => 'Test Doc Book',
        'author' => 'Test Author',
        'description' => 'Test Description',
        'price' => 10.00,
        'category' => 'Test',
        'isbn' => '123456789',
        'pages' => 100,
        'published_year' => 2024,
        'stock' => 10,
        'is_featured' => false,
        'is_free' => false,
    ];

    $response = $this->post(route('admin.books.store'), array_merge($bookData, [
        'cover_image' => UploadedFile::fake()->image('cover.jpg'),
        'book_pdf' => UploadedFile::fake()->create('test.doc', 1024, 'application/msword'),
    ]));

    $response->assertRedirect();
    $this->assertDatabaseHas('books', ['title' => 'Test Doc Book']);
});

test('book controller validates word file extensions', function () {
    $this->actingAs($this->admin);

    $bookData = [
        'title' => 'Test Book',
        'author' => 'Test Author',
        'description' => 'Test Description',
        'price' => 10.00,
        'category' => 'Test',
        'isbn' => '123456789',
        'pages' => 100,
        'published_year' => 2024,
        'stock' => 10,
        'is_featured' => false,
        'is_free' => false,
    ];

    $response = $this->post(route('admin.books.store'), array_merge($bookData, [
        'cover_image' => UploadedFile::fake()->image('cover.jpg'),
        'book_pdf' => UploadedFile::fake()->image('test.txt'),
    ]));

    $response->assertSessionHasErrors('book_pdf');
});

test('pdf generator service can generate from word file', function () {
    $book = Book::create([
        'title' => 'Test Word Book',
        'author' => 'Test Author',
        'price' => 10.00,
        'book_pdf' => 'test.docx',
    ]);

    $pdfService = app(PdfGeneratorService::class);

    expect($pdfService->hasWordFile($book))->toBeTrue();
});

test('pdf generator service detects word file correctly', function () {
    $bookWithPdf = Book::create([
        'title' => 'Test PDF Book',
        'author' => 'Test Author',
        'price' => 10.00,
        'book_pdf' => 'test.pdf',
    ]);

    $bookWithWord = Book::create([
        'title' => 'Test Word Book',
        'author' => 'Test Author',
        'price' => 10.00,
        'book_pdf' => 'test.docx',
    ]);

    $pdfService = app(PdfGeneratorService::class);

    expect($pdfService->hasWordFile($bookWithPdf))->toBeFalse();
    expect($pdfService->hasWordFile($bookWithWord))->toBeTrue();
});
