<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Annotations as OA;



/**
 * @OA\Info(
 *    title="StickerWave-API",
 *    description="Sticker wave Api",
 *    version="1.0.0",
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    /**
     * @OA\Get(
     *     path="documentation",
     *     @OA\Response(response="200", description="Documentation endpoint to prevent crashes")
     * )
     */
    public function documentation()
    {
    }
}
