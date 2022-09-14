<?php
use Illuminate\Support\Str;

function getURL()
{
    $route_current = Route::current();
    $controller = $route_current->action['controller'];
    $indexAction = explode('@', substr($controller, 21))[0] . '@index';
    $url = action($indexAction);
    $baseUrl = URL::to('/');
    $length = strlen($baseUrl);
    $uri = substr($url, $length + 1);
    return $uri;
}

function getRouteBaseName()
{
    $route = explode(".", Route::currentRouteName())[0];
    return $route;
}

function getRouteName()
{
    $route = Route::currentRouteName();
    return $route;
}

// function getPermissionArray()
// {
//     $user = Auth::user();
//     $permission = [];
//     $roles = $user->roles;
//     foreach ($roles as $role) {
//         $permissionName = $role->getPermissionNames()->toArray();
//         $permission = array_merge($permission, $permissionName);
//     }
//     return $permission;
// }

function res_type($type)
{
    switch ($type) {
        case 'success':
            return 'success';
            break;
        case 'error':
            return 'error';
            break;
    }
}

/**
 * Return tittle response
 *
 */
function res_title($title)
{
    switch ($title) {
        case 'success':
            return "Resource access successful";
            break;
        case 'error':
            return "There was an error in processing, please try again!";
            break;
        case 'validate_error':
            return "There are some problems";
            break;
        case 'notfound':
            return "Resource does not exist!";
            break;
        case 'permission':
            return "You do not have access to this resource!";
            break;
        case 'notfound_or_permission':
            return "Resource does not exist or you do not have access to this resource!";
            break;
    }
}

/**
 * Return content response
 *
 */
function res_content($content)
{
    switch ($content) {
        case 'empty':
            return [];
            break;
    }
}

/**
 * Return http status code response
 *
 */
function res_code($code)
{
    switch ($code) {
        /* success */
        case 200:
            return 200;
            break;

        /* validate */
        case 400:
            return 400;
            break;

        /* Permission */
        case 401:
            return 401;
            break;

        /* Notfound */
        case 404:
            return 404;
            break;

        /* serve error */
        case 500:
            return 500;
            break;
    }
}

/**
 * Return uuid
 *
 */
function uuid()
{
    return Str::orderedUuid()->toString();
}

/**
 * Return default response from the application.
 *
 * @return \Illuminate\Http\Response
 */
function defaultReponseError()
{
    return response()->json(
        [
            'type' => res_type('error'),
            'title' => 'There was an error in processing, please try again!',
            'content' => res_content('empty'),
        ],
        res_code('500')
    );
}
