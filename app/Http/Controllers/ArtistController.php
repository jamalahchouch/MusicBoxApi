<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
/**
 * @OA\Tag(
 *     name="Artists",
 *     description="API pour gérer les artistes"
 * )
 */


class ArtistController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/artists",
     *     summary="Liste des artistes",
     *     tags={"Artists"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Numéro de page pour pagination",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="genre",
     *         in="query",
     *         description="Filtrer par genre musical",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste paginée d'artistes"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $artists = Artist::with('albums')
            ->when($request->genre, fn($q) => $q->where('genre', $request->genre))
            ->paginate(10);
            

        return response()->json($artists);
    }
      /**
     * @OA\Post(
     *     path="/api/artists",
     *     tags={"Artists"},
     *     summary="Créer un nouvel artiste",
     *     description="Ajoute un artiste dans la base de données",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","genre"},
     *             @OA\Property(property="name", type="string", example="Freddie Mercury"),
     *             @OA\Property(property="genre", type="string", example="Rock")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Artiste créé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=10),
     *             @OA\Property(property="name", type="string", example="Freddie Mercury"),
     *             @OA\Property(property="genre", type="string", example="Rock")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Requête invalide")
     * )
     */
  
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'genre' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        $artist = Artist::create($validated);

        return response()->json($artist, 201);
    }
        /**
     * @OA\Put(
     *     path="/api/artists/{id}",
     *     tags={"Artists"},
     *     summary="Mettre à jour un artiste",
     *     description="Met à jour les informations d’un artiste existant",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'artiste",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Mick Jagger"),
     *             @OA\Property(property="genre", type="string", example="Rock")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Artiste mis à jour",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Mick Jagger"),
     *             @OA\Property(property="genre", type="string", example="Rock")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Artiste non trouvé")
     * )
     */
    public function show(Artist $artist)
    {
        return response()->json($artist->load('albums'));
    }

     public function ListArtist()
    {
        $artists = Artist::all();

        return response()->json([
            'message' => '✅ Liste des artistes récupérée avec succès',
            'artists' => $artists
        ], 200);
    }
       /**
     * @OA\Delete(
     *     path="/api/artists/{id}",
     *     tags={"Artists"},
     *     summary="Supprimer un artiste",
     *     description="Supprime un artiste de la base de données",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'artiste",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Artiste supprimé avec succès"
     *     ),
     *     @OA\Response(response=404, description="Artiste non trouvé")
     * )
     */
    public function update(Request $request, Artist $artist)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'genre' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        $artist->update($validated);

        return response()->json($artist);
    }

    public function destroy(Artist $artist)
    {
        $artist->delete();
        return response()->json(null, 204);
    }
}
