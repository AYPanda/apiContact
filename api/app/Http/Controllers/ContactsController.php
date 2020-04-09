<?php


namespace App\Http\Controllers;


use App\Contact;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class ContactsController
{

    public function __construct()
    {
    }

    public function create(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        $contacts = [];

        foreach ($content["items"] as $key => $value) {

            $value["phone"] = $this->parsePhone($value["phone"]);

            $date = new \DateTime();
            $yesterday = $date->modify("-1 day");

            $countContact = Contact::whereRaw("phone = ? and source_id = ? and created_at > ?",
                [$value["phone"],
                    $content["source_id"],
                    $yesterday
                ])->count();

            if ($countContact == 0) {
                array_push($contacts, $this->createContact($value, $content["source_id"]));
            }
        }

        //Еще как вариант ускорить вставку можно попробовать отключать индексы перед вставкой, и включать после нее.
        Contact::insert($contacts);

        return count($contacts);
    }

    public function get(Request $request)
    {
        $phone = $this->parsePhone($request->input("phone"));

        return Contact::wherePhone($phone)->get();
    }


    private function createContact($value, $source_id)
    {
        $contact["name"] = $value["name"];
        $contact["email"] = $value["email"];
        $contact["source_id"] = $source_id;
        $contact["phone"] = $value["phone"];
        $contact["created_at"] = new \DateTime();

        return $contact;
    }

    private function parsePhone($phone)
    {
        $phone = preg_replace('![^0-9]+!', '', $phone);

        if (mb_strlen($phone) == 11 && ($phone[0] == "7" || $phone[0] == "8")) {
            $phone = substr($phone, 1);
        }

        return $phone;
    }
}
