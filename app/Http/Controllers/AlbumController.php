<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/albums",
     *     tags={"Albums"},
     *     summary="Lister tous les albums",
     *     description="Retourne la liste paginée des albums",
     *     @OA\Response(
     *         response=200,
     *         description="Succès",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Thriller"),
     *                 @OA\Property(property="artist_id", type="integer", example=1),
     *                 @OA\Property(property="release_year", type="integer", example=1982)
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $albums = Album::with('artist')
            ->when($request->year, fn($q) => $q->where('year', $request->year))
            ->paginate(1);

        return response()->json($albums);
    }
      /**
     * @OA\Post(
     *     path="/api/albums",
     *     tags={"Albums"},
     *     summary="Créer un nouvel album",
     *     description="Ajoute un album dans la base de données",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","artist_id","release_year"},
     *             @OA\Property(property="title", type="string", example="Thriller"),
     *             @OA\Property(property="artist_id", type="integer", example=1),
     *             @OA\Property(property="release_year", type="integer", example=1982)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Album créé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=10),
     *             @OA\Property(property="title", type="string", example="Thriller"),
     *             @OA\Property(property="artist_id", type="integer", example=1),
     *             @OA\Property(property="release_year", type="integer", example=1982)
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'nullable|integer',
            'artist_id' => 'required|exists:artists,id',
        ]);

        $album = Album::create($validated);

        return response()->json($album, 201);
    }
    /**
     * @OA\Put(
     *     path="/api/albums/{id}",
     *     tags={"Albums"},
     *     summary="Mettre à jour un album",
     *     description="Met à jour les informations d’un album existant",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'album",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Bad"),
     *             @OA\Property(property="artist_id", type="integer", example=1),
     *             @OA\Property(property="release_year", type="integer", example=1987)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Album mis à jour",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Bad"),
     *             @OA\Property(property="artist_id", type="integer", example=1),
     *             @OA\Property(property="release_year", type="integer", example=1987)
     *         )
     *     ),
     *     @OA\Response(response=404, description="Album non trouvé")
     * )
     */
    public function show(Album $album)
    {
        return response()->json($album->load('songs'));
    }

    public function update(Request $request, Album $album)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'nullable|integer',
            'artist_id' => 'required|exists:artists,id',
        ]);

        $album->update($validated);

        return response()->json($album);
    }
  /**
     * @OA\Delete(
     *     path="/api/albums/{id}",
     *     tags={"Albums"},
     *     summary="Supprimer un album",
     *     description="Supprime un album de la base de données",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'album",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Album supprimé avec succès"
     *     ),
     *     @OA\Response(response=404, description="Album non trouvé")
     * )
     */
    public function destroy(Album $album)
    {
        $album->delete();
        return response()->json(null, 204);
    }
}
