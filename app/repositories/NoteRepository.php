<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 9/12/2018
 * Time: 8:40 AM
 */

namespace App\repositories;


use App\base\IStatus;
use App\Exceptions\ApiException;
use App\Note;
use App\Organization;
use App\User;
use Illuminate\Contracts\Logging\Log;

class NoteRepository
{
    /**
     * @param Organization $organization
     * @param User $user
     * @param array $data
     * @return Note|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|object|static|static[]
     */
    public function storeNote(Organization $organization,User $user, $data = [])
    {
        if(!empty(array_get($data,'note_id'))){
            $note = $organization->notes()->where('id' , array_get($data,'note_id'))->first();
        }
        if(empty($note)){
            $note = new Note();
        }

        $oldValue = $note->title;
        $note->title = array_get($data,'note');
        $note->description = array_get($data,'note');
        $note->status = IStatus::ACTIVE;
        $note->member_id = array_get($data,'member_id');
        $note->organization_id = $organization->id;
        $note->user_id = $user->id;
        $note->save();

        (new MemberRepository())->addMemberChangeLog($note->member,'note_title',$oldValue,$note->title);
        $note = $note->where(['id' => $note->id])->with('user')->get();

        return $note;
    }

    public function delete(Organization $organization, $id)
    {
        try{
            $organization->notes()->where('id', $id)->delete();
        }catch (\Exception $exception){
            \Log::info('Note Delete Error: ' . $exception->getMessage(). PHP_EOL. 'Org: '. $organization->id. ' Note #: '. $id);
            throw new ApiException(null,['error' => 'Unable to delete note. Please Contact Administrator.']);
        }
    }
}