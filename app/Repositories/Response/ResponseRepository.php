<?php

namespace App\Repositories\Response;

use App\Models\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\AbstractRepository;

class ResponseRepository extends AbstractRepository
{
    public function model(): string
    {
        return Response::class;
    }

    public function get()
    {
        return $this->model->get();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete($id): bool
    {
        DB::beginTransaction();
        try {
            $response = $this->model->find($id);
            if (empty($response)) {
                return false;
            }
            $response->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return false;
        }
        return true;
    }

    /**
     * @param $updateId
     * @return bool
     */
    public function deleteByUpdateId($updateId): bool
    {
        return $this->delete($this->findWhere(['update_id' => $updateId])->first()->id);
    }
}
