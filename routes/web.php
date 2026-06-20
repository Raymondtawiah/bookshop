<?php

use App\Http\Controllers\Admin\CoachingController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaystackWebhookController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\VisaTrainingController;
use App\Http\Controllers\WebinarController;
use App\Http\Controllers\WebinarRegistrationController;
use App\Http\Controllers\WebinarWaitingListController;
use App\Models\Discount;
use App\Models\User;
use App\Services\VerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

// Chat routes (public - accessible to all)
Route::middleware(['web'])->group(function () {
    Route::post('chat', [ChatController::class, 'store'])->name('chat.store');
    Route::get('chat/messages', [ChatController::class, 'customerChats'])->name('chat.messages');
    Route::get('chat/unread', [ChatController::class, 'getUnreadCount'])->name('chat.unread');
    Route::post('chat/read', [ChatController::class, 'markAsRead'])->name('chat.read');
    Route::delete('chat/clear', [ChatController::class, 'clearAllChats'])->name('chat.clear');
    Route::delete('chat/{id}', [ChatController::class, 'clearChatMessage'])->name('chat.delete');

    Route::post('/visa/start', function () {
        $country = request('country');
        $type = request('type');

        $prompt = "You are a U.S. visa interview officer. Ask ONE realistic visa interview question based on the applicant's {$type} visa to {$country}.

For F1 Student visa, use these topics in order:
1. Study purpose: Why do you want to study in the United States? Why did you choose this university? Why this major? How does this relate to your career? Why not study in your home country?
2. University choice: How many universities did you apply to? Why this specific university? Did you get admission elsewhere?
3. Financial: Who is sponsoring? What does your sponsor do? How will you pay? Can you show proof of funds? Do you have a scholarship?
4. Family: What do your parents do? Do you have siblings? Who do you live with?
5. Academic: What did you study previously? What are your grades? Have you studied abroad before?
6. Future plans: What are your plans after graduation? Will you return home? Do you plan to work in the U.S.?
7. Immigration intent: Have you been refused a visa before? Do you have relatives in the U.S.? Why should we believe you will return?

For B1/B2 Business/Tourist visa, ask about: purpose of visit, travel details, family/friends in the U.S., employment, finances, home country ties, travel history, and return plans.

Pick the next logical topic based on the conversation flow. Return ONLY the question.";

        $response = Http::withToken(env('GROQ_API_KEY'))
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ]
            ]);

        return $response->json();
    });

    Route::post('/visa/evaluate', function () {
        $question = request('question');
        $answer = request('answer');

        $prompt = "You are a strict but professional U.S. visa interview officer. Evaluate the applicant's answer below using proper punctuation and concise, natural English.

If the answer is unclear, misspelled, or nonsensical, respond ONLY with: 'I didn't catch that. Please rephrase your answer.'

Otherwise, evaluate and return JSON only in this exact format:
{
  \"clarity\": \"score out of 10\",
  \"confidence\": \"score out of 10\",
  \"honesty\": \"low | medium | high\",
  \"risk_flag\": \"yes | no\",
  \"feedback\": \"short improvement advice\"
}

Question: {$question}
Answer: {$answer}";

        $response = Http::withToken(env('GROQ_API_KEY'))
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.5
            ]);

        return $response->json();
    });

    Route::post('visa-training/chat', [VisaTrainingController::class, 'chat'])->name('visa-training.chat');
    Route::get('visa-training/reset', [VisaTrainingController::class, 'reset'])->name('visa-training.reset');
});

// Home and public routes - NO middleware needed
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('search', [HomeController::class, 'search'])->name('search');
Route::get('discounts', function () {
    return view('discounts');
})->name('discounts');
Route::post('free-book/lead', [ProductController::class, 'createLead'])->name('free-book.lead');
Route::get('free-book/download/{token}', [ProductController::class, 'downloadByToken'])->name('free-book.download');

// Cart routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('cart', [CartController::class, 'viewCart'])->name('cart');
    Route::put('cart/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('checkout', [CartController::class, 'checkout'])->name('checkout');
});

// Customer dashboard (protected by auth middleware)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
});

Route::get('visa-tip', function () {
    return view('visa-tip');
})->name('visa-tip');


Route::get('/visa', function () {
    return view('visa-interview');
})->name('visa');

Route::get('/visa-training', function () {
    return view('visa-training', [
        'conversationHistory' => [],
        'currentStep' => 0,
        'totalQuestions' => 8,
        'isCompleted' => false,
        'evaluation' => null,
        'interviewMode' => true,
    ]);
})->name('visa-training');

// Privacy Policy - Public route (no authentication required)
// Is for someone having mobile app needing a privacy policy url
// so pardon the me Thank you.
Route::get('realgalaxyfc_privacy', function () {
    return view('privacy');
})->name('privacy');

// Webinar routes (public - accessible to all)</thinking>
Route::get('webinars', [WebinarController::class, 'index'])->name('webinars.index');
Route::get('webinar/{webinar}', [WebinarController::class, 'show'])->name('webinars.show')->where('webinar', '[0-9]+');
Route::get('webinar/{webinar}/register-page', [WebinarController::class, 'registerPage'])->name('webinars.register.page')->where('webinar', '[0-9]+');

// Webinar registration (public - guests allowed)
Route::post('webinar/{webinar}/register', [WebinarRegistrationController::class, 'storeRegistration'])
    ->name('webinars.register.store')->where('webinar', '[0-9]+');

// Webinar payment routes (guests allowed)
Route::get('webinar/{webinar}/payment/{registration}', [WebinarRegistrationController::class, 'payment'])
    ->name('webinars.payment')->where('webinar', '[0-9]+');
Route::post('webinar/{webinar}/payment/initiate/{registration}', [WebinarRegistrationController::class, 'initializePayment'])
    ->name('webinars.payment.initiate')->where('webinar', '[0-9]+');
Route::get('webinar/payment/callback', [WebinarRegistrationController::class, 'paymentCallback'])
    ->name('webinars.payment.callback');

// Webinar routes - requires auth
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('webinar/{webinar}/register', [WebinarRegistrationController::class, 'register'])
        ->name('webinars.register')->where('webinar', '[0-9]+');
    Route::get('webinar/{webinar}/join', [WebinarRegistrationController::class, 'join'])
        ->name('webinars.join')->where('webinar', '[0-9]+');
    Route::get('webinar/{webinar}/verify/{registration}', [WebinarRegistrationController::class, 'showVerification'])
        ->name('webinars.verify.join')->where('webinar', '[0-9]+');
    Route::post('webinar/{webinar}/verify/{registration}', [WebinarRegistrationController::class, 'processVerification'])
        ->name('webinars.verify.process')->where('webinar', '[0-9]+');
    Route::get('webinar/{webinar}/verified/{registration}', [WebinarRegistrationController::class, 'showVerifiedJoin'])
        ->middleware(['protect.webinar.link'])
        ->name('webinars.join.verified')->where('webinar', '[0-9]+');
});

// Public webinar access via encrypted link
Route::get('webinar/{webinar}/access/{token}', [WebinarRegistrationController::class, 'access'])
    ->name('webinars.access')->where('webinar', '[0-9]+');

// Webinar waiting list routes
Route::post('webinar/{webinar}/waiting-list/join', [WebinarWaitingListController::class, 'join'])
    ->name('webinars.waiting-list.join')->where('webinar', '[0-9]+');

Route::get('webinar/{webinar}/waiting-list', [WebinarWaitingListController::class, 'index'])
    ->name('webinars.waiting-list.index')->where('webinar', '[0-9]+');

Route::delete('webinar/{webinar}/waiting-list/leave', [WebinarWaitingListController::class, 'leave'])
    ->name('webinars.waiting-list.leave')->where('webinar', '[0-9]+');

require __DIR__.'/settings.php';
require __DIR__.'/admin.php';
