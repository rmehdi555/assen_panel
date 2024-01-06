<span class="text-primary-600">تعداد تیکت باز : {{ \App\Models\Ticket::whereNot('status_id', 7)->where('department_id', $getRecord()->id)->count() }}</span>
