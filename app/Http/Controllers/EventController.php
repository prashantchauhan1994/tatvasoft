<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\EventList;

class EventController extends Controller
{
    public function index()
    {
        $title = 'Events';
        return view('event.index', compact('title'));
    }

    public function ajax(Request $request)
    {
        $columns = array('id', 'title', 'dates', 'Occurence', 'action');

        $events = new Event;
        $totalData = $events->count();
        $totalFiltered = $events->count();

        $limit = $request->input('length');
        $start = $request->input('start');

        $dir = "desc";
        $order = "created_at";
        if(isset($columns[$request->input('order.0.column')]))
        {
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
        }

        if($request->input('search.value') != "")
        {
            $search = $request->input('search.value');

            $events = $events->where('title','LIKE',"%{$search}%");

            $totalFiltered = $events->count();

        }
        $events = $events->offset($start)->limit($limit)->orderBy($order,$dir)->get();

        $data = array();
        if($events->count() > 0)
        {
            foreach ($events as $event)
            {
                $nestedData['id'] = $event->id;
                $nestedData['title'] = $event->title;
                $nestedData['dates'] = $event->start_date ." to ".$event->end_date;
                $nestedData['occurence'] = str_replace("@"," ",$event->recurrence_at);

                $action = '<div class="tb-icon-wrap">';
                $action .= '<a href="'.route('event.view',$event->id).'" class="btn btn-info btn-xs action-btn" role="button" aria-pressed="true"><i class="fa fa-eye"></i></a>';
                $action .= '<a href="'.route('event.edit',$event->id).'" class="btn btn-success btn-xs action-btn" role="button" aria-pressed="true"><i class="fa fa-pencil"></i></a>';
                $action .= '<a href="javascript:void(0);" data-id='.$event->id.' class="btnDelete btn btn-danger btn-xs action-btn" role="button" aria-pressed="true"><i class="fa fa-trash-o"></i></a>';

                $nestedData['action'] = $action;

                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }

    public function add()
    {
        $title = "Add Event";
        $mode = "add";
        $event = new Event;
        return view('event.add',compact('title', 'mode', 'event'));
    }

    public function getEventDates($request)
    {
        $eventDates = [];
        $fromDate = $request->start_date;
        $toDate = $request->end_date;
        if($request->recurrence_type == "repeat") {
            $day = strtolower(date("l",strtotime($fromDate)));
            $startDate = Carbon::parse($fromDate)->modify('this '.$day);
            $endDate = Carbon::parse($toDate);

            if($request->repeat_type1 == "Every") {
                $add = 0;
            } else if($request->repeat_type1 == "Every Other") {
                $add = 2;
            } else if($request->repeat_type1 == "Every Third") {
                $add = 3;
            } else if($request->repeat_type1 == "Every Fourth") {
                $add = 4;
            }

            if($request->repeat_type2 == "Day") {
                if($add > 0) {
                    for ($date = $startDate; $date->lte($endDate); $date->addDay($add)) {
                        $eventDates[] = $date->format('Y-m-d');
                    }
                } else {
                    for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                        $eventDates[] = $date->format('Y-m-d');
                    }
                }

            } else if($request->repeat_type2 == "Week") {
                if($add > 0) {
                    for ($date = $startDate; $date->lte($endDate); $date->addWeek($add)) {
                        $eventDates[] = $date->format('Y-m-d');
                    }
                } else {
                    for ($date = $startDate; $date->lte($endDate); $date->addWeek()) {
                        $eventDates[] = $date->format('Y-m-d');
                    }
                }
            } else if($request->repeat_type2 == "Month") {
                if($add > 0) {
                    for ($date = $startDate; $date->lte($endDate); $date->addMonth($add)) {
                        $eventDates[] = $date->format('Y-m-d');
                    }
                } else {
                    for ($date = $startDate; $date->lte($endDate); $date->addMonth()) {
                        $eventDates[] = $date->format('Y-m-d');
                    }
                }
            } else if($request->repeat_type2 == "Year") {
                if($add > 0) {
                    for ($date = $startDate; $date->lte($endDate); $date->addYear($add)) {
                        $eventDates[] = $date->format('Y-m-d');
                    }
                } else {
                    for ($date = $startDate; $date->lte($endDate); $date->addYear()) {
                        $eventDates[] = $date->format('Y-m-d');
                    }
                }
            }
        }
        else if($request->recurrence_type == "repeat_on") {
            $day = strtolower($request->repeat_on_type2);
            $startDate = Carbon::parse($fromDate)->modify('this '.$day);
            $endDate = Carbon::parse($toDate);

            $add = 0;
            if($request->repeat_on_type3 == "3 Months") {
                $add = 3;
            } else if($request->repeat_on_type3 == "4 Months") {
                $add = 4;
            } else if($request->repeat_on_type3 == "Year") {
                $add = 12;
            }

            if($add > 0) {
                for ($date = $startDate; $date->lte($endDate); $date->addMonth($add)) {
                    $eventDates[] = date("Y-m-d", strtotime($request->repeat_on_type1." ".$request->repeat_on_type2." of ".$date->format('M')." ".$date->format('Y').""));
                }
            } else {
                for ($date = $startDate; $date->lte($endDate); $date->addMonth()) {
                    $eventDates[] = date("Y-m-d", strtotime($request->repeat_on_type1." ".$request->repeat_on_type2." of ".$date->format('M')." ".$date->format('Y').""));
                }
            }
        }
        return $eventDates;
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => "required",
            'start_date' => "required|date_format:Y-m-d",
            'end_date' => "required|date_format:Y-m-d",
            'recurrence_type' => "required|in:repeat,repeat_on",
        ]);
        $eventDates = $this->getEventDates($request);


        if($request->recurrence_type == "repeat") {
            $recurrence_at = $request->repeat_type1."@".$request->repeat_type2;
        } else {
            $recurrence_at = $request->repeat_on_type1."@".$request->repeat_on_type2."@".$request->repeat_on_type3;
        }

        $event = Event::create([
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'recurrence_type' => $request->recurrence_type,
            'recurrence_at' => $recurrence_at
        ]);
        if(count($eventDates) > 0) {
            foreach ($eventDates as $eventDate) {
                EventList::create([
                    'event_id' => $event->id,
                    'date' => $eventDate
                ]);
            }
        }
        return redirect(route('event.index'))->with(['alert-class' => 'success', 'message' => "Event Added successfully!"]);
    }

    public function edit($id)
    {
        $event = Event::find($id);
        if(!$event) {
            return redirect("/");
        }
        $title = "Update Event";
        $mode = "update";
        $recurrence_at = explode("@",$event->recurrence_at);
        return view('event.add',compact('title', 'mode', 'event','recurrence_at'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => "required",
            'start_date' => "required|date_format:Y-m-d",
            'end_date' => "required|date_format:Y-m-d",
            'recurrence_type' => "required|in:repeat,repeat_on",
        ]);
        $eventDates = $this->getEventDates($request);


        if($request->recurrence_type == "repeat") {
            $recurrence_at = $request->repeat_type1."@".$request->repeat_type2;
        } else {
            $recurrence_at = $request->repeat_on_type1."@".$request->repeat_on_type2."@".$request->repeat_on_type3;
        }
        EventList::where('event_id',$request->id)->delete();
        Event::where('id',$request->id)->update([
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'recurrence_type' => $request->recurrence_type,
            'recurrence_at' => $recurrence_at
        ]);
        if(count($eventDates) > 0) {
            foreach ($eventDates as $eventDate) {
                EventList::create([
                    'event_id' => $request->id,
                    'date' => $eventDate
                ]);
            }
        }
        return redirect(route('event.index'))->with(['alert-class' => 'success', 'message' => "Event Updated successfully!"]);
    }

    public function view($id)
    {
        $event = Event::find($id);
        if(!$event) {
            return redirect("/");
        }
        $title = "View Event";
        return view('event.view',compact('title', 'event'));
    }

    public function delete($id)
    {
        $event = Event::find($id);
        if(!$event) {
            return response()->json(['success' => 0]);
        }
        $event->delete();
        return response()->json(['success' => 1,'message'=>"Deleted successfully!"]);
    }
}
