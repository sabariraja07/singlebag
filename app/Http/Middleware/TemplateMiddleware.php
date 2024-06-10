<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Shop;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\View\FileViewFinder;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class TemplateMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $paths = [];
    $app = app();

    /*
     *  Pull our template from our site name
     */
    $template = "singlebag/views";
    if($template)
    {
        $paths = [
            $app['config']['view.paths.templates'] . DIRECTORY_SEPARATOR . $template
        ];
    }
    // realpath(base_path('themes/singlebag/views')),

    /*
     *  Default view path is ALWAYS last
     */
    $paths[] = $app['config']['view.paths.default'];

    /*
     * Overwrite the view finder paths
     */
    $finder = new FileViewFinder(app()['files'], $paths);
    View::setFinder($finder);

    return $next($request);
  }
}