<?php
/**
 * Created by IntelliJ IDEA.
 * User: mryan
 * Date: 18.10.18
 * Time: 16:29
 */

namespace App\Http\Controllers;


use App\Event;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //
    }

    private static $fieldsOrder;

    private static function sortFields($a, $b)
    {
        foreach (self::$fieldsOrder as $orderItem) {
            if ($orderItem->field_id === $a) {
                $aOrder = $orderItem->order;
            }
            if ($orderItem->field_id === $b) {
                $bOrder = $orderItem->order;
            }
        }
        return $aOrder - $bOrder;
    }

    public function export($id)
    {

        $this->authorize('admin');
        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        $event = Event::findOrFail($id);
        $header = ["Person Name", "Department", "Email", "Will you attend this event?", "Why not? (optional)"];
        foreach ($event->answerFields as $field) {
            array_push($header, $field->label);
        }

        $fieldsById = $event->fields->mapWithKeys(function ($item) {
            return [$item->id => $item];
        });

        $csv->insertOne($header);

        $records = DB::table('registration_values')
            ->join('event_registrations', 'registration_values.event_registration_id', '=', 'event_registrations.id')
            ->join('users', 'event_registrations.user_id', '=', 'users.id')
            ->join('fields', 'fields.id', '=', 'registration_values.field_id')
            ->select('registration_values.value as value', 'fields.label as fieldname', 'fields.id as fieldid', 'users.name as username',
                'users.department as department', 'users.email as email')
            ->where('event_registrations.event_id', $id)
            ->where('fields.type',"!=",  "header")
            ->get();


        self::$fieldsOrder = DB::table('event_fields')
            ->select('field_id', 'order')
            ->where('event_id', $id)
            ->get();


        $departmentOrder = new \stdClass();
        $departmentOrder->field_id = 'department';
        $departmentOrder->order = 1;
        self::$fieldsOrder->push($departmentOrder);

        $emailOrder = new \stdClass();
        $emailOrder->field_id = 'email';
        $emailOrder->order = 2;
        self::$fieldsOrder->push($emailOrder);

        $participatingOrder = new \stdClass();
        $participatingOrder->field_id = 1;
        $participatingOrder->order = 3;
        self::$fieldsOrder->push($participatingOrder);

        $notParticipatingReason = new \stdClass();
        $notParticipatingReason->field_id = 2;
        $notParticipatingReason->order = 4;
        self::$fieldsOrder->push($notParticipatingReason);

        $map = array();

        foreach ($records as $record) {
            $map[$record->username]['department'] = $record->department;
            $map[$record->username]['email'] = $record->email;
            $map[$record->username][$record->fieldid] = $record->value;
        }

        // hide irrelevant answers (conditional fields)
        foreach ($map as $username => $answers) {
            foreach ($answers as $fieldid => $value) {
                // why not ?
                if ($fieldid == 2 && $answers[1] === "Yes") {
                    $map[$username][$fieldid] = "";
                }

                if ($fieldid < 3 || $fieldid === "department" || $fieldid === "email") {
                    continue;
                }

                $condition = $fieldsById[$fieldid]->condition;
                if (!empty($condition)) {
                    $conditionFieldId = preg_split("~:~", $condition)[0];
                    $conditionValue = preg_split("~:~", $condition)[1];
                    if ($answers[$conditionFieldId] !== $conditionValue) {
                        $map[$username][$fieldid] = "";
                    }
                }

                // non participating
                if ($answers[1] === "No") {
                    $map[$username][$fieldid] = "";
                }

            }
        }

        // sort by username (column)
        ksort($map);

        // insert in CSV
        foreach ($map as $username => $answers) {
            uksort($answers, array("App\Http\Controllers\AdminController", "sortFields"));
            $row = [$username];
            foreach ($answers as $fieldid => $value) {
                array_push($row, $value);
            }
            $csv->insertOne($row);
        }
        $csv->output($event->name . '.csv');
    }
}
