<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Quest;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class ClientController extends Controller implements Resource
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::query()
            ->orderBy('last_name')
            ->orderBy('first_name');
        if(\Request::ajax()){
           if($query = $this->filterQuery()) {
                $clients
                    ->where('first_name', 'like', $query)
                    ->orWhere('last_name', 'like', $query)
                    ->orWhere(\DB::raw("concat(first_name, ' ', last_name)"), 'like', $query)
                    ->orWhere('email', 'like', $query)
                    ->orWhere('phone', 'like', $query)
                    ->orWhere('vk_account_id', 'like', $query);
           }
           return response()->json($clients->limit(10)->get());
        }
        $clients = $clients
            ->paginate(self::PAGE_COUNT);
        return view('admin.bookings.index', [
            'bookings' => $clients,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $game = new Game();
        $game->name = 'Новая игра';

        return view('admin.bookings.show', [
            'game' => $game,
            'types' => GameType::getSelectList('name'),
            'locations' => Location::getSelectList('name'),
            'teams' => Team::getSelectList('name'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = $request->validate([
            'start_at' => 'date_format:d.m.Y H:i',
            'location_id' => 'integer',
            'type_id' => 'integer',
            'name' => 'string',
        ]);
        $data['start_at'] = Carbon::createFromFormat('d.m.Y H:i', $data['start_at']);
        $data = new Game($data);
        $data->save();
        return redirect()->route('admin.bookings.show', ['id' => $data->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $booking = Booking::query()
            ->with('quest', 'client', 'status')
            ->find($id);

        if (!$booking) {
            return redirect()->route('admin.bookings.create');
        }

        return view('admin.bookings.show', [
            'booking' => $booking,
            'quests' => Quest::getSelectList('name', false),
            'statuses' => Status::getSelectList('sort', false),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return redirect()->route('admin.bookings.show', ['id' => $id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $game = Game::find($id);
        if (!$game) {
            return redirect()->route('admin.bookings.index');
        }
        $data = $request->validate([
            'start_at' => 'nullable|date_format:d.m.Y H:i',
            'location_id' => 'integer',
            'type_id' => 'integer',
            'name' => 'string',
            'image' => 'nullable|image',
        ]);
        $data['start_at'] = Carbon::createFromFormat('d.m.Y H:i', $data['start_at']);
        $file = @$data['image'];
        if ($game->image && ($file || $request->input('image_remove'))) {
            \Storage::delete($game->imagePath);
            $data['image'] = '';
        }
        if ($file) {
            /**
             * @var UploadedFile $file
             */
            $data['image'] = $file->hashName();
            $file->storeAs($game->imageRoot, $data['image']);
        }
        Game::find($id)->update($data);
        return redirect()->route('admin.bookings.show', ['id' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
