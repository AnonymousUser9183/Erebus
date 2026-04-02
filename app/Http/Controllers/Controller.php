<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Base Controller Class - Foundation for All Application Controllers
 * =========================================================================
 * 
 * This is the base controller class that all application controllers extend.
 * It includes traits for authorization and request validation, providing
 * common functionality across the entire application.
 * 
 * Traits:
 *   - AuthorizesRequests: Provides authorization/gate methods
 *   - ValidatesRequests: Provides convenient validation methods
 * 
 * Usage:
 *   class YourController extends Controller { ... }
 */

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Base Controller Class
 *
 * Provides core functionality for all application controllers including:
 * - Authorization checks via policies and gates
 * - Request validation with convenient validation methods
 * 
 * All application controllers should extend this class to inherit
 * these essential traits and functionality.
 *
 * @see \Illuminate\Foundation\Auth\Access\AuthorizesRequests
 * @see \Illuminate\Foundation\Validation\ValidatesRequests
 * @see \Illuminate\Routing\Controller
 */
class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;
}
