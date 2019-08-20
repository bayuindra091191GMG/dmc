<?php


namespace App\Http\Controllers\Admin\Registration;


use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RegistrationStudentController extends Controller
{
    public function formStepOneMuaythai(Request $request){
        if(!empty($request->student_id)){
            $student = Customer::find($request->student_id);

            if(empty($student)){
                dd('INVALID STUDENT!');
            }
        }

        $data = [
            'type'          => 1,
            'courseType'    => 'MUAYTHAI',
            'student'       => $student ?? null
        ];

        return view('admin.registrations.customers.step_1_customer')->with($data);
    }

    public function formStepOneDance(){
        $data = [
            'type'          => 2,
            'courseType'    => 'DANCE'
        ];

        return view('admin.registrations.customers.step_1_customer')->with($data);
    }

    public function formStepOnePrivate(){
        $data = [
            'type'          => 3,
            'courseType'    => 'PRIVATE'
        ];

        return view('admin.registrations.customers.step_1_customer')->with($data);
    }
    public function formStepOneGymnastic(){
        $data = [
            'type'          => 4,
            'courseType'    => 'GYMNASTIC'
        ];

        return view('admin.registrations.customers.step_1_customer')->with($data);
    }

    public function store(Request $request){
        // Validate student
        if(!empty($request->input('is_new_student'))){
            if(empty($request->input('student_name')) || empty($request->input('student_email'))){
                return redirect()->back()->withErrors('Nama dan alamat email murid wajib diisi!', 'default')->withInput($request->all());
            }
            else{
                // Validate unique student name
                $nameFound = Customer::where('name', 'LIKE', '%'. $request->input('student_name'). '%')->first();
                if(!empty($nameFound)){
                    return redirect()->back()->withErrors('Nama murid sudah terdaftar!', 'default')->withInput($request->all());
                }

                // Validate unique student email
                $emailFound = Customer::where('email', $request->input('student_email'))->first();
                if(!empty($emailFound)){
                    return redirect()->back()->withErrors('Alamat email sudah terdaftar!', 'default')->withInput($request->all());
                }
            }
        }
        else{
            if(empty($request->input('customer'))){
                return redirect()->back()->withErrors('Pilih murid!', 'default')->withInput($request->all());
            }
        }

        if(!empty($request->input('is_new_student'))){
            // Create student if new
            $dob = null;
            if(!empty($request->input('dob'))){
                $dob = Carbon::createFromFormat('d M Y', $request->input('dob'), 'Asia/Jakarta');
            }

            $newStudent = Customer::create([
                'member_id'     => $request->input('member_id') ?? null,
                'barcode'       => $request->input('barcode') ?? null,
                'name'          => $request->input('student_name'),
                'phone'         => $request->input('student_phone') ?? null,
                'email'         => $request->input('student_email'),
                'address'       => $request->input('student_address') ?? null,
                'age'           => $request->input('age') ?? null,
                'dob'           => $dob !== null ? $dob->toDateTimeString() : null,
                'parent_name'   => $request->input('student_parent_name') ?? null
            ]);

            $studentId = $newStudent->id;
        }
        else{
            // Get existing student id
            $studentId = $request->input('customer_id');
        }

        return redirect()->route('admin.registration.step-two', ['type' => $request->input('type'), 'student_id' => $studentId]);
    }
}