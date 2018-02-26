<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Quest;
use App\Models\Status;
use Carbon\Carbon;

class BookingController extends Controller implements Resource
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookings = Booking::query()
            ->with('quest', 'client', 'status')
            ->orderBy('date', 'asc');
        $date = $this->filterDate();
        if (!$date) {
            $date = [
                Carbon::now()->format('d.m.Y'),
                Carbon::now()->addDays(13)->format('d.m.Y'),
            ];
        }
        if (@$date[0] && ($dateStart = Carbon::parse($date[0])->toDateTimeString())) {
            $bookings->where('date', '>=', $dateStart);
        }
        if (@$date[1] && ($dateEnd = Carbon::parse($date[1])->addDay()->toDateTimeString())) {
            $bookings->where('date', '<', $dateEnd);
        }
        $bookings = $bookings
            ->paginate(self::PAGE_COUNT);
        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'date' => $date,
            'filter' => \Request::input('filter'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /**
         * @var Booking $booking
         */
        $booking = new Booking();

        return view('admin.bookings.show', [
            'booking' => $booking,
            'title' => 'Новая бронь',
            'quests' => Quest::getSelectList('name', false),
            'statuses' => Status::getSelectList('sort', false),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     * @throws \bafoed\VKAPI\Facades\VkApiException|\Exception
     */
    public function store()
    {
        $data = \Request::validate([
            'quest_id' => 'required|integer',
            'status_id' => 'required|integer',
            'client_id' => 'nullable|integer',
            'date' => 'required|date_format:d.m.Y H:i',
            'amount' => 'nullable|integer',
            'price' => 'nullable|integer',
            'comment' => 'nullable|string',
        ]);
        $dataClient = \Request::validate([
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'email' => 'nullable|string|email',
            'phone' => 'nullable|string',
            'vk_account_id' => 'nullable|string',
        ]);
        $data['date'] = Carbon::parse($data['date']);

        \DB::beginTransaction();
        try {
            if (!$data['client_id'] && array_filter($dataClient)) {
                $dataClient['vk_account_id'] = ClientController::getVkAccountId($dataClient['vk_account_id']);
                $dataClient['phone'] = preg_replace(['/^\+?[7|8]/', '/[^0-9]/'], '', $dataClient['phone']);
                $client = Client::create($dataClient);
                $data['client_id'] = $client->id;
            }
            $booking = Booking::create($data);
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->withErrors([
                'Возникла ошибка при сохранении',
                $e->getMessage(),
            ]);
        }
        \DB::commit();
        return redirect()->route('admin.bookings.show', ['id' => $booking->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /**
         * @var Booking $booking
         */
        $booking = Booking::query()
            ->with('quest', 'client', 'status', 'history')
            ->find($id);

        if (!$booking) {
            abort(404, 'Бронь не найдена');
        }

        return view('admin.bookings.show', [
            'booking' => $booking,
            'title' => $booking->quest->name . ' - ' . $booking->dateFormatted,
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     * @throws \bafoed\VKAPI\Facades\VkApiException|\Exception
     */
    public function update($id)
    {
        $data = \Request::validate([
            'quest_id' => 'required|integer',
            'status_id' => 'required|integer',
            'client_id' => 'nullable|integer',
            'date' => 'required|date_format:d.m.Y H:i',
            'amount' => 'nullable|integer',
            'price' => 'nullable|integer',
            'comment' => 'nullable|string',
        ]);
        $dataClient = \Request::validate([
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'email' => 'nullable|string|email',
            'phone' => 'nullable|string',
            'vk_account_id' => 'nullable|string',
        ]);
        $data['date'] = Carbon::parse($data['date']);

        $booking = Booking::find($id);
        if (!$booking) {
            return redirect()->back()->withErrors([
                'Бронь не найдена',
            ]);
        }
        \DB::beginTransaction();
        try {
            if (!$data['client_id'] && array_filter($dataClient)) {
                $dataClient['vk_account_id'] = ClientController::getVkAccountId($dataClient['vk_account_id']);
                $dataClient['phone'] = preg_replace(['/^\+?[7|8]/', '/[^0-9]/'], '', $dataClient['phone']);
                $client = Client::create($dataClient);
                $data['client_id'] = $client->id;
            }
            $booking->update($data);
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->withErrors([
                'Возникла ошибка при сохранении',
                $e->getMessage(),
            ]);
        }
        \DB::commit();
        return redirect()->route('admin.bookings.show', ['id' => $booking->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     */
    public function destroy($id)
    {
        return abort(404);
    }
}
