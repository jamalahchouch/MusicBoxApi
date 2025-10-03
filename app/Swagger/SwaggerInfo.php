<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="MusicBox API",
 *     description="Documentation interactive de l'API MusicBox (Artistes, Albums, Chansons)"
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Serveur local"
 * )
 *
 * @OA\Tag(
 *     name="Artists",
 *     description="Gestion des artistes"
 * )
 *
 * @OA\Tag(
 *     name="Albums",
 *     description="Gestion des albums"
 * )
 *
 * @OA\Tag(
 *     name="Songs",
 *     description="Gestion des chansons"
 * )
 *  @OA\Tag(
 *     name="Auth",
 *     description="Authentification et gestion des utilisateurs (login, register)"
 * )
 */
class SwaggerInfo {}
