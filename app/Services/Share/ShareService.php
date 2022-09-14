<?php

namespace App\Services\Share;

use Auth;
use DB;

class ShareService
{
    private $pivotTable;
    public function __construct(DB $db)
    {
        $this->pivotTable = $db::table('userables');
    }

    /**
     * Find shared by uuid
     *
     * @param  $uuid
     */
    public function findSharedByUuid($uuid)
    {
        return $this->pivotTable->whereUuid($uuid)->first();
    }

    /**
     * Update role by uuid
     *
     * @param  $request
     * @return bool
     */
    public function updateRoleByUuid($request)
    {
       return  $this->pivotTable->whereUuid($request['share_uuid'])->update(['role' => $request['role']]);
    }

    /**
     * Update role resource and associate
     * @param  $request
     * @return bool
     */
    public function updateResourceAndAssociateRole($request)
    {
        return $this->pivotTable->whereUuid($request['shared_uuid'])->orWhere('parent', $request['shared_uuid'])->update(['role' => $request['role']]);
    }

    /**
     * Delete resource and associate
     * @param  $request
     * @return bool
     */
    public function deleteResourceAndAssociate($request)
    {
        return $this->pivotTable->whereUuid($request['shared_uuid'])->orWhere('parent', $request['shared_uuid'])->delete();
    }


    /**
     * Share resources to users
     *
     * @param  $query, $recepient , $request
     */
    public function shareBrowserToUser($query, $recepient, $request)
    {
        $query->attach($recepient->id, ['sharers_id' => Auth::id(), 'uuid' => uuid(), 'role' => $request['role']]);
    }

    /**
     * Share resources to users
     *
     * @param $relation , $user, $data
     */
    public function shareResourceToUser($relation, $user, $data)
    {
        $data = [
            'uuid' => uuid(),
            'sharers_id' => Auth::id(),
            'role' => $data['role'],
            /* 'type' => $data['type'], */
        ];
        $relation->attach($user->id, $data);

        return $data;
    }

    /**
     * Share multiple browser to user
     *
     * @param $relation , $user, $data
     */
    public function shareMultipleResource($relation, $data)
    {
        return $relation->syncWithoutDetaching($data);
    }

    /**
     * Check the record already exists in the pivot table
     *
     * @param  $query, $model
     * @return bool
     */
    public function hasPivot($query, $model)
    {
        return (bool) $query->wherePivot($model->getForeignKey(), $model->{$model->getKeyName()})->count();
    }

    /**
     * unshare
     *
     * @param  $request
     * @return bool
     */
    public function unshare($request)
    {
       return  $this->pivotTable->whereUuid($request['share_uuid'])->delete();
    }

    /**
     * Format data before acttach multiple
     *
     * @param  $ids, $data
     * @return array
     */
    public function formatDataBeforeAttach($ids, $data)
    {
        $arr = [];
        foreach ($ids as $key => $value) {

            $arr[$value] = [
                'uuid' => uuid(), //uuid of share
                'sharers_id' => Auth::id(),
                'role' => $data['role'],
                /*  'type' => $data['type'], */
                'parent' => $data['parent'], //uuid of parent
            ];
        }
        return $arr;
    }

}
