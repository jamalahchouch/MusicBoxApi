<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;


class SongController extends Controller
{
        /**
     * @OA\Get(
     *     path="/api/songs",
     *     tags={"Songs"},
     *     summary="Lister toutes les chansons",
     *     description="Retourne la liste paginée des chansons",
     *     @OA\Response(
     *         response=200,
     *         description="Succès",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Billie Jean"),
     *                 @OA\Property(property="album_id", type="integer", example=1),
     *                 @OA\Property(property="duration", type="string", example="04:15")
     *             )
     *         )
     *     )
     * )
     */

    public function index(Request $request)
    {
        $songs = Song::with('album.artist')
            ->when($request->duration_min, fn($q) => $q->where('duration', '>=', $request->duration_min))
            ->when($request->duration_max, fn($q) => $q->where('duration', '<=', $request->duration_max))
            ->paginate(10);

        return response()->json($songs);
    }

    /**
     * @OA\Post(
     *     path="/api/songs",
     *     tags={"Songs"},
     *     summary="Créer une nouvelle chanson",
     *     description="Ajoute une chanson dans la base de données",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","album_id","duration"},
     *             @OA\Property(property="title", type="string", example="Billie Jean"),
     *             @OA\Property(property="album_id", type="integer", example=1),
     *             @OA\Property(property="duration", type="string", example="04:15")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Chanson créée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=10),
     *             @OA\Property(property="title", type="string", example="Billie Jean"),
     *             @OA\Property(property="album_id", type="integer", example=1),
     *             @OA\Property(property="duration", type="string", example="04:15")
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'nullable|integer',
            'album_id' => 'required|exists:albums,id',
        ]);

        $song = Song::create($validated);

        return response()->json($song, 201);
    }
    /**
     * @OA\Put(
     *     path="/api/songs/{id}",
     *     tags={"Songs"},
     *     summary="Mettre à jour une chanson",
     *     description="Met à jour les informations d’une chanson existante",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la chanson",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Beat It"),
     *             @OA\Property(property="album_id", type="integer", example=1),
     *             @OA\Property(property="duration", type="string", example="04:18")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chanson mise à jour",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Beat It"),
     *             @OA\Property(property="album_id", type="integer", example=1),
     *             @OA\Property(property="duration", type="string", example="04:18")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Chanson non trouvée")
     * )
     */
    public function show(Song $song)
    {
        return response()->json($song->load('album.artist'));
    }

    public function update(Request $request, Song $song)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'nullable|integer',
            'album_id' => 'required|exists:albums,id',
        ]);

        $song->update($validated);

        return response()->json($song);
    }

     /**
     * @OA\Delete(
     *     path="/api/songs/{id}",
     *     tags={"Songs"},
     *     summary="Supprimer une chanson",
     *     description="Supprime une chanson de la base de données",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la chanson",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Chanson supprimée avec succès"
     *     ),
     *     @OA\Response(response=404, description="Chanson non trouvée")
     * )
     */

    public function destroy(Song $song)
    {
        $song->delete();
        return response()->json(null, 204);
    }
/** 
 *    @OA\Get(
 *     path="/api/ad/songs/search",
 *     summary="Rechercher des chansons",
 *     description="Recherche des chansons par titre ou par artiste (avec pagination)",
 *     tags={"Songs"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Parameter(
 *         name="query",
 *         in="query",
 *         description="Mot-clé à rechercher (titre de la chanson ou nom de l'artiste)",
 *         required=true,
 *         @OA\Schema(type="string", example="Queen")
 *     ),
 *
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="Numéro de page pour la pagination",
 *         required=false,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Résultats trouvés",
 *         @OA\JsonContent(
 *             @OA\Property(property="current_page", type="integer", example=1),
 *             @OA\Property(property="data", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="title", type="string", example="Bohemian Rhapsody"),
 *                     @OA\Property(property="album", type="object",
 *                         @OA\Property(property="id", type="integer", example=10),
 *                         @OA\Property(property="name", type="string", example="A Night at the Opera"),
 *                         @OA\Property(property="artist", type="object",
 *                             @OA\Property(property="id", type="integer", example=3),
 *                             @OA\Property(property="name", type="string", example="Queen")
 *                         )
 *                     )
 *                 )
 *             ),
 *             @OA\Property(property="total", type="integer", example=50),
 *             @OA\Property(property="per_page", type="integer", example=10),
 *             @OA\Property(property="last_page", type="integer", example=5)
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=400,
 *         description="Paramètre query manquant"
 *     ),
 *
 *     @OA\Response(
 *         response=404,
 *         description="Aucun résultat trouvé"
 *     )
 * )
 */

    // Recherche par titre ou artiste
   public function searchSongs(Request $request)
{
    $q = $request->query('query'); // 👈 au lieu de get('q')

    if (!$q) {
        return response()->json(['message' => 'Veuillez fournir un mot-clé'], 400);
    }

    $songs = Song::with('album.artist')
        ->where('title', 'like', "%{$q}%")
        ->orWhereHas('album.artist', fn($query) => $query->where('name', 'like', "%{$q}%"))
        ->paginate(10);

    return response()->json($songs);
}

    

    /**
     * 🎤 Filtrer les chansons d’un artiste donné
     * Exemple : /api/songs/by-artist/1
     */
    public function byArtist($artist_id)
    {
        $songs = Song::whereHas('album', function ($q) use ($artist_id) {
                $q->where('artist_id', $artist_id);
            })
            ->with('album.artist')
            ->get();

        if ($songs->isEmpty()) {
            return response()->json(['message' => 'Aucune chanson trouvée pour cet artiste'], 404);
        }

        return response()->json($songs);
    }
}
