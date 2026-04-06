<?php
use App\Http\Controllers\PrivateFileController;

/*
 * =========================================================================
 * © 2026 The Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Erebus Marketplace Script Laravel 12 Web Routes
 * =========================================================================
 */

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\GuestProductController;
use App\Http\Controllers\GuestProfileController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\GuidesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReferencesController;
use App\Http\Controllers\RulesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\DisputesController;
use App\Http\Controllers\VendorsController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BecomeVendorController;
use App\Http\Controllers\ReturnAddressController;
use App\Http\Controllers\PrivateMirrorRequestController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\VendorMiddleware;
use App\Http\Middleware\CheckBanned;

// =========================================================================
// PUBLIC ROUTES - No Auth Required
// =========================================================================

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return redirect()->route('guest-products.index');
})->name('login-home');

// ===== GUEST PRODUCTS ROUTES - PUBLIC (NO AUTH REQUIRED) =====
Route::get('/guest-products', [GuestProductController::class, 'index'])->name('guest-products.index');
Route::get('/guest-products/{id}', [GuestProductController::class, 'show'])->name('guest-products.show');
Route::get('/guest-product-picture/{filename}', [PrivateFileController::class, 'guestProductPicture'])->name('guest-product.picture');
Route::get('/guest-profile-picture/{filename}', [PrivateFileController::class, 'profilePicture'])->name('guest.profile.picture');

// Public routes for harm reduction and guides
Route::get('/verify-mirror', function () {
    return view('auth.verify-mirror-standalone');
})->name('verify-mirror');

Route::get('/harm-reduction', function () {
    return view('auth.harm-reduction-standalone');
})->name('harm-reduction');

Route::get('/harm-reduction/fentanyl-test-strips', function () {
    return view('auth.fentanyl-test-strips-show');
})->name('fentanyl-test-strips');

Route::get('/harm-reduction/marquis-reagent-test', function () {
    return view('auth.marquis-reagent-test-show');
})->name('marquis-reagent-test');

Route::get('/harm-reduction/mecke-reagent-test', function () {
    return view('auth.mecke-reagent-test-show');
})->name('mecke-reagent-test');

Route::get('/harm-reduction/narcan-guide', function () {
    return view('auth.narcan-show');
})->name('narcan-guide');

Route::get('/harm-reduction/safe-consumption-practices', function () {
    return view('auth.safe-consumption-practices-show');
})->name('safe-consumption-practices');

Route::get('/harm-reduction/recovery-support', function () {
    return view('auth.recovery-support-show');
})->name('recovery-support');

Route::get('/harm-reduction/crisis-hotline', function () {
    return view('auth.crisis-hotline-show');
})->name('crisis-hotline');

Route::get('/harm-reduction/therapy-and-counseling', function () {
    return view('auth.therapy-counseling-show');
})->name('therapy-counseling');

Route::get('/harm-reduction/good-samaritan-laws', function () {
    return view('auth.good-samaritan-laws-show');
})->name('good-samaritan-laws');

Route::get('/harm-reduction/legal-awareness', function () {
    return view('auth.legal-awareness-show');
})->name('legal-awareness');

// Public text file routes
Route::get('/omg.txt', fn() => response()->file(public_path('omg.txt'), ['Content-Type' => 'text/plain; charset=utf-8']));
Route::get('/pgp.txt', fn() => response()->file(public_path('pgp.txt'), ['Content-Type' => 'text/plain; charset=utf-8']));
Route::get('/mirrors.txt', fn() => response()->file(public_path('mirrors.txt'), ['Content-Type' => 'text/plain; charset=utf-8']));
Route::get('/canary.txt', fn() => response()->file(public_path('canary.txt'), ['Content-Type' => 'text/plain; charset=utf-8']));
Route::get('/related.txt', fn() => response()->file(public_path('related.txt'), ['Content-Type' => 'text/plain; charset=utf-8']));

// =========================================================================
// GUEST ROUTES - For Unauthenticated Users
// =========================================================================

Route::middleware('guest')->group(function () {
    // Auth routes
    Route::get('/banned', [AuthController::class, 'showBanned'])->name('banned');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->withoutMiddleware('guest');
    Route::post('/login', [AuthController::class, 'login']);

    // Password reset routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'verifyMnemonic'])->name('password.verify');
    Route::get('/reset-password', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'reset'])->name('password.update');
    Route::get('/mnemonic/{token}', [AuthController::class, 'showMnemonic'])->name('show.mnemonic');

    // 2FA routes
    Route::get('/2fa-challenge', [AuthController::class, 'showPgp2FAChallenge'])->name('pgp.2fa.challenge');
    Route::post('/2fa-verify', [AuthController::class, 'verifyPgp2FAChallenge'])->name('pgp.2fa.verify');
});

// =========================================================================
// AUTHENTICATED USER ROUTES
// =========================================================================

Route::middleware(['auth', CheckBanned::class])->group(function () {
    // Home and authentication
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Products
    Route::get('/search', [ProductController::class, 'index'])->name('products.index');
    Route::get('/search/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/product-picture/{filename}', [PrivateFileController::class, 'productPicture'])->name('product.picture');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/{cart}/message', [CartController::class, 'saveMessage'])->name('cart.message.save');
    Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::delete('/wishlist', [WishlistController::class, 'clearAll'])->name('wishlist.clear');

    // Guides
    Route::get('/guides', [GuidesController::class, 'index'])->name('guides.index');
    Route::get('/guides/keepassxc', [GuidesController::class, 'keepassxc'])->name('guides.keepassxc');
    Route::get('/guides/monero', [GuidesController::class, 'monero'])->name('guides.monero');
    Route::get('/guides/tor', [GuidesController::class, 'tor'])->name('guides.tor');
    Route::get('/guides/kleopatra', [GuidesController::class, 'kleopatra'])->name('guides.kleopatra');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings/change-password', [SettingsController::class, 'changePassword'])->name('settings.changePassword');
    Route::post('/settings/update-pgp-key', [SettingsController::class, 'updatePgpKey'])->name('settings.updatePgpKey');
    Route::post('/settings/update-secret-phrase', [SettingsController::class, 'updateSecretPhrase'])->name('settings.updateSecretPhrase');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [MessageController::class, 'startConversation'])->name('messages.start');
    Route::get('/messages/{conversation}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{conversation}', [MessageController::class, 'store'])->name('messages.store');
    Route::delete('/messages/{conversation}', [MessageController::class, 'destroy'])->name('messages.destroy');

    // Support requests
    Route::get('/support', [SupportController::class, 'index'])->name('support.index');
    Route::get('/support/create', [SupportController::class, 'create'])->name('support.create');
    Route::post('/support', [SupportController::class, 'store'])->name('support.store');
    Route::get('/support/{supportRequest}/{ticketId}', [SupportController::class, 'show'])->name('support.show');
    Route::post('/support/{supportRequest}/{ticketId}/solve-reply-pow', [SupportController::class, 'solvePowReply'])->name('support.solve-reply-pow');
    Route::post('/support/{supportRequest}/{ticketId}/reply', [SupportController::class, 'reply'])->name('support.reply');
    Route::post('support/solve-pow-create', [SupportController::class, 'solvePowCreate'])->name('support.solve-pow-create');

    // Disputes
    Route::get('/disputes', [DisputesController::class, 'index'])->name('disputes.index');
    Route::get('/disputes/{id}', [DisputesController::class, 'show'])->name('disputes.show');
    Route::post('/disputes/{uniqueUrl}', [DisputesController::class, 'store'])->name('disputes.store');
    Route::post('/disputes/{id}/message', [DisputesController::class, 'addMessage'])->name('disputes.add-message');

    // Rules
    Route::get('/rules', [RulesController::class, 'index'])->name('rules');

    // Dashboard
    Route::get('/dashboard/{username?}', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/account', [ProfileController::class, 'index'])->name('profile');
    Route::post('/account', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/account/picture', [ProfileController::class, 'deleteProfilePicture'])->name('profile.deletepicture');
    Route::get('/account/picture/{filename}', [ProfileController::class, 'getProfilePicture'])->name('profile.picture');

    // PGP key confirmation
    Route::get('/pgp/confirm', [ProfileController::class, 'showPgpConfirmationForm'])->name('pgp.confirm');
    Route::post('/pgp/confirm', [ProfileController::class, 'confirmPgpKey'])->name('pgp.confirm.submit');

    // PGP 2FA settings
    Route::put('/pgp/2fa', [AuthController::class, 'updatePgp2FASettings'])->name('pgp.2fa.update');

    // References
    Route::get('/references', [ReferencesController::class, 'index'])->name('references.index');
    Route::post('/references/{vendor}', [ReferencesController::class, 'storeVendorReference'])->name('references.store');
    Route::delete('/references/{id}', [ReferencesController::class, 'removeVendorReference'])->name('references.remove');

    // Becoming a vendor
    Route::get('/become-vendor', [BecomeVendorController::class, 'index'])->name('become.vendor');
    Route::get('/become-vendor/payment', [BecomeVendorController::class, 'payment'])->name('become.payment');
    Route::get('/become-vendor/application', [BecomeVendorController::class, 'showApplication'])->name('become.vendor.application');
    Route::post('/become-vendor/application', [BecomeVendorController::class, 'submitApplication'])->name('become.vendor.submit-application');

    // Return addresses
    Route::get('/return-addresses', [ReturnAddressController::class, 'index'])->name('return-addresses.index');
    Route::post('/return-addresses', [ReturnAddressController::class, 'store'])->name('return-addresses.store');
    Route::delete('/return-addresses/{returnAddress}', [ReturnAddressController::class, 'destroy'])->name('return-addresses.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/{notification}/delete', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Private Mirror Requests - USER SIDE
    Route::post('/profile/private-mirror-request', [ProfileController::class, 'requestPrivateMirror'])->name('profile.private-mirror.request');

    // Vendors listing
    Route::get('/vendors', [VendorsController::class, 'index'])->name('vendors.index');
    Route::get('/vendors/{username}', [VendorsController::class, 'show'])->name('vendors.show');

    // Orders
    Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
    Route::get('/orders/{uniqueUrl}', [OrdersController::class, 'show'])->name('orders.show');
    Route::post('/orders', [OrdersController::class, 'store'])->name('orders.store');
    Route::post('/orders/{uniqueUrl}/mark-sent', [OrdersController::class, 'markAsSent'])->name('orders.mark-sent');
    Route::post('/orders/{uniqueUrl}/mark-completed', [OrdersController::class, 'markAsCompleted'])->name('orders.mark-completed');
    Route::post('/orders/{uniqueUrl}/mark-cancelled', [OrdersController::class, 'markAsCancelled'])->name('orders.mark-cancelled');
    Route::post('/orders/{uniqueUrl}/review/{orderItemId}', [OrdersController::class, 'submitReview'])->name('orders.submit-review');
});

// =========================================================================
// ADMIN ROUTES
// =========================================================================

Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    // Canary
    Route::get('/admin/canary', [AdminController::class, 'showUpdateCanary'])->name('admin.canary');
    Route::post('/admin/canary', [AdminController::class, 'updateCanary'])->name('admin.canary.post');

    // Statistics
    Route::get('/admin/statistics', [AdminController::class, 'statistics'])->name('admin.statistics');

    // Logs
    Route::get('/admin/logs', [AdminController::class, 'showLogs'])->name('admin.logs');
    Route::get('/admin/logs/{type}', [AdminController::class, 'showLogsByType'])->where('type', 'error|warning|info')->name('admin.logs.show');
    Route::delete('/admin/logs/{type}', [AdminController::class, 'deleteLogs'])->where('type', 'error|warning|info')->name('admin.logs.delete');
    Route::delete('/admin/logs/{type}/selected', [AdminController::class, 'deleteSelectedLogs'])->where('type', 'error|warning|info')->name('admin.logs.delete-selected');

    // Users
    Route::get('/admin/users', [AdminController::class, 'userList'])->name('admin.users');
    Route::get('/admin/users/{user}', [AdminController::class, 'userDetails'])->name('admin.users.details');
    Route::put('/admin/users/{user}/roles', [AdminController::class, 'updateUserRoles'])->name('admin.users.update-roles');
    Route::post('/admin/users/{user}/ban', [AdminController::class, 'banUser'])->name('admin.users.ban');
    Route::post('/admin/users/{user}/unban', [AdminController::class, 'unbanUser'])->name('admin.users.unban');

    // Disputes
    Route::get('/admin/disputes', [DisputesController::class, 'adminIndex'])->name('admin.disputes.index');
    Route::get('/admin/disputes/{id}', [DisputesController::class, 'adminShow'])->name('admin.disputes.show');
    Route::post('/admin/disputes/{id}/vendor-prevails', [DisputesController::class, 'resolveVendorPrevails'])->name('admin.disputes.vendor-prevails');
    Route::post('/admin/disputes/{id}/buyer-prevails', [DisputesController::class, 'resolveBuyerPrevails'])->name('admin.disputes.buyer-prevails');

    // Support requests
    Route::get('/admin/support', [AdminController::class, 'supportRequests'])->name('admin.support.requests');
    Route::get('/admin/support/{supportRequest}/{ticketId}', [AdminController::class, 'showSupportRequest'])->name('admin.support.show');
    Route::post('/admin/support/{supportRequest}/{ticketId}/reply', [AdminController::class, 'replySupportRequest'])->name('admin.support.reply');
    Route::put('/admin/support/{supportRequest}/{ticketId}/status', [AdminController::class, 'updateSupportStatus'])->name('admin.support.status');

    // Bulk messaging
    Route::get('/admin/bulk-message', [AdminController::class, 'showBulkMessage'])->name('admin.bulk-message.create');
    Route::post('/admin/bulk-message', [AdminController::class, 'sendBulkMessage'])->name('admin.bulk-message.send');
    Route::get('/admin/bulk-message/list', [AdminController::class, 'listBulkMessages'])->name('admin.bulk-message.list');
    Route::delete('/admin/bulk-message/{notification}', [AdminController::class, 'deleteBulkMessage'])->name('admin.bulk-message.delete');

    // Categories
    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::post('/admin/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::delete('/admin/categories/{category}', [AdminController::class, 'deleteCategory'])->name('admin.categories.delete');
    Route::get('/admin/categories/list', [AdminController::class, 'listCategories'])->name('admin.categories.list');

    // Vendor Applications
    Route::get('/admin/vendor-applications', [AdminController::class, 'vendorApplications'])->name('admin.vendor-applications.index');
    Route::get('/admin/vendor-applications/{application}', [AdminController::class, 'showVendorApplication'])->name('admin.vendor-applications.show');
    Route::post('/admin/vendor-applications/{application}/accept', [AdminController::class, 'acceptVendorApplication'])->name('admin.vendor-applications.accept');
    Route::post('/admin/vendor-applications/{application}/deny', [AdminController::class, 'denyVendorApplication'])->name('admin.vendor-applications.deny');

    // Private Mirror Requests - ADMIN SIDE
    Route::get('/admin/private-mirror-requests', [PrivateMirrorRequestController::class, 'adminIndex'])->name('admin.private-mirror-requests.list');
    Route::get('/admin/private-mirror-requests/{requestId}', [PrivateMirrorRequestController::class, 'privateMirrorRequestsShow'])->name('admin.private-mirror-requests.show');
    Route::post('/admin/private-mirror-requests/{requestId}/assign', [PrivateMirrorRequestController::class, 'privateMirrorRequestsAssign'])->name('admin.private-mirror-requests.assign');
    Route::post('/admin/private-mirror-requests/{requestId}/deny', [PrivateMirrorRequestController::class, 'privateMirrorRequestsDeny'])->name('admin.private-mirror-requests.deny');

    // Pop-ups
    Route::get('/admin/pop-up', [AdminController::class, 'popupIndex'])->name('admin.popup.index');
    Route::get('/admin/pop-up/create', [AdminController::class, 'popupCreate'])->name('admin.popup.create');
    Route::post('/admin/pop-up', [AdminController::class, 'popupStore'])->name('admin.popup.store');
    Route::post('/admin/pop-up/{popup}/activate', [AdminController::class, 'popupActivate'])->name('admin.popup.activate');
    Route::delete('/admin/pop-up/{popup}', [AdminController::class, 'popupDestroy'])->name('admin.popup.destroy');

    // Products
    Route::get('/admin/all-products', [AdminController::class, 'allProducts'])->name('admin.all-products');
    Route::get('/admin/products/{product}/edit', [AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::patch('/admin/products/{product}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/admin/products/{product}', [AdminController::class, 'destroyProduct'])->name('admin.products.destroy');
    Route::post('/admin/products/{product}/feature', [AdminController::class, 'featureProduct'])->name('admin.products.feature');
    Route::post('/admin/products/{product}/unfeature', [AdminController::class, 'unfeatureProduct'])->name('admin.products.unfeature');
});

// =========================================================================
// VENDOR ROUTES
// =========================================================================

Route::middleware(['auth', VendorMiddleware::class])->group(function () {
    Route::get('/vendor', [VendorController::class, 'index'])->name('vendor.index');
    Route::get('/vendor/appearance', [VendorController::class, 'showAppearance'])->name('vendor.appearance');
    Route::post('/vendor/appearance', [VendorController::class, 'updateAppearance'])->name('vendor.appearance.update');

    // My products
    Route::get('/vendor/my-products', [VendorController::class, 'myProducts'])->name('vendor.my-products');
    Route::delete('/vendor/products/{product}', [VendorController::class, 'destroy'])->name('vendor.products.destroy');

    // Sales
    Route::get('/vendor/sales', [VendorController::class, 'sales'])->name('vendor.sales');
    Route::get('/vendor/sales/{uniqueUrl}', [VendorController::class, 'showSale'])->name('vendor.sales.show');
    Route::post('/vendor/sales/{uniqueUrl}/update-delivery-text', [VendorController::class, 'updateDeliveryText'])->name('vendor.sales.update-delivery-text');

    // Disputes
    Route::get('/vendor/disputes', [DisputesController::class, 'vendorDisputes'])->name('vendor.disputes.index');
    Route::get('/vendor/disputes/{id}', [DisputesController::class, 'vendorShow'])->name('vendor.disputes.show');

    // Advertisement
    Route::get('/vendor/advertisement/rate-limit', [VendorController::class, 'showRateLimit'])->name('vendor.advertisement.rate-limit');
    Route::get('/vendor/advertisement/create/{product}', [VendorController::class, 'createAdvertisement'])->name('vendor.advertisement.create');
    Route::post('/vendor/advertisement/{product}', [VendorController::class, 'storeAdvertisement'])->name('vendor.advertisement.store');
    Route::get('/vendor/advertisement/{identifier}/payment', [VendorController::class, 'showAdvertisementPayment'])->name('vendor.advertisement.payment');

    // Products
    Route::get('/vendor/products/{type}/create', [VendorController::class, 'create'])->where('type', 'cargo|digital|deaddrop')->name('vendor.products.create');
    Route::post('/vendor/products/{type}', [VendorController::class, 'store'])->where('type', 'cargo|digital|deaddrop')->name('vendor.products.store');
    Route::get('/vendor/products/{product}/edit', [VendorController::class, 'edit'])->name('vendor.products.edit');
    Route::patch('/vendor/products/{product}', [VendorController::class, 'update'])->name('vendor.products.update');
});

// =========================================================================
// FALLBACK ROUTE
// =========================================================================

Route::fallback(function () {
    if (auth()->check()) {
        return redirect()->route('home');
    }
    return redirect()->route('guest-products.index');
});
