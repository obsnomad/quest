<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use Illuminate\Support\Collection;

class ClientController extends Controller implements Resource
{
    /**
     * @param $vkAccountId
     * @param string $return
     * @return $this
     * @throws \bafoed\VKAPI\Facades\VkApiException
     */
    public static function getVkAccountId($vkAccountId, $return = 'redirect')
    {
        if ($vkAccountId) {
            $vkAccountId = preg_replace('/.*vk\.com\//', '', $vkAccountId);
            try {
                $vkAccount = collect(\VKAPI::call('users.get', [
                    'user_ids' => $vkAccountId,
                    'fields' => 'screen_name',
                ]))->first();
                if ($return != 'redirect') {
                    return $vkAccount[$return];
                }
                return $vkAccount['uid'];
            } catch (\Exception $e) {
                if ($return == 'redirect') {
                    return redirect()->back()->withErrors([
                        'Не удалось получить данные из VK. Удалить значение в поле "Аккаунт VK".',
                        $e->getMessage(),
                    ]);
                }
            }
        }
        return null;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \bafoed\VKAPI\Facades\VkApiException
     */
    public function index()
    {
        $clients = Client::query()
            ->orderBy('last_name')
            ->orderBy('first_name');
        if ($query = $this->filterQuery()) {
            $clients
                ->where('first_name', 'like', $query)
                ->orWhere('last_name', 'like', $query)
                ->orWhere(\DB::raw("concat(first_name, ' ', last_name)"), 'like', $query)
                ->orWhere('email', 'like', $query)
                ->orWhere('phone', 'like', $query)
                ->orWhere('vk_account_id', 'like', $query);
        }
        if (\Request::ajax()) {
            return response()->json($clients->limit(10)->get());
        }
        $clients = $clients
            ->paginate(self::PAGE_COUNT);
        /**
         * @var Collection $vkAccounts
         */
        $vkAccounts = $clients->map(function ($client) {
            /**
             * @var Client $client
             */
            return $client->vkAccountId;
        });
        if ($vkAccounts) {
            try {
                $vkAccounts = collect(\VKAPI::call('users.get', [
                    'user_ids' => $vkAccounts->implode(','),
                    'fields' => 'screen_name',
                ]))->keyBy('uid');
            } catch (\Exception $e) {
            }
        }
        return view('admin.clients.index', [
            'clients' => $clients,
            'vkAccounts' => $vkAccounts,
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
         * @var Client $client
         */
        $client = new Client();

        return view('admin.clients.show', [
            'client' => $client,
            'title' => 'Новый клиент',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     * @throws \bafoed\VKAPI\Facades\VkApiException
     */
    public function store()
    {
        $data = \Request::validate([
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'email' => 'nullable|string|email',
            'phone' => 'nullable|string',
            'vk_account_id' => 'nullable|string',
        ]);

        \DB::beginTransaction();
        try {
            $data['vk_account_id'] = self::getVkAccountId($data['vk_account_id']);
            $data['phone'] = preg_replace(['/^\+?[7|8]/', '/[^0-9]/'], '', $data['phone']);
            $client = Client::create($data);
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->withErrors([
                'Возникла ошибка при сохранении',
                $e->getMessage(),
            ]);
        }
        \DB::commit();
        return redirect()->route('admin.clients.show', ['id' => $client->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \bafoed\VKAPI\Facades\VkApiException|\Exception
     */
    public function show($id)
    {
        /**
         * @var Client $client
         */
        $client = Client::query()
            ->with('bookings.quest', 'bookings.status')
            ->find($id);

        if (!$client) {
            abort(404, 'Клиент не найден');
        }

        $client->vkAccountLink = self::getVkAccountId($client['vk_account_id'], 'screen_name');

        return view('admin.clients.show', [
            'client' => $client,
            'title' => $client->fullName,
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
        return redirect()->route('admin.clients.show', ['id' => $id]);
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
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'email' => 'nullable|string|email',
            'phone' => 'nullable|string',
            'vk_account_id' => 'nullable|string',
        ]);

        $client = Client::find($id);
        if (!$client) {
            return redirect()->back()->withErrors([
                'Клиент не найден',
            ]);
        }
        \DB::beginTransaction();
        try {
            $data['vk_account_id'] = self::getVkAccountId($data['vk_account_id']);
            $data['phone'] = preg_replace(['/^\+?[7|8]/', '/[^0-9]/'], '', $data['phone']);
            $client->update($data);
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->withErrors([
                'Возникла ошибка при сохранении',
                $e->getMessage(),
            ]);
        }
        \DB::commit();
        return redirect()->route('admin.clients.show', ['id' => $client->id]);
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
