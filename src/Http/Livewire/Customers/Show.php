<?php

namespace Shopper\Framework\Http\Livewire\Customers;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Shopper\Framework\Repositories\UserRepository;

class Show extends Component
{
    /**
     * Listeners.
     *
     * @var string[]
     */
    protected $listeners = ['profileUpdate'];

    /**
     * Customer Model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $customer;

    /**
     * Customer Model id.
     *
     * @var int
     */
    public $user_id;

    /**
     * Last Name attribute.
     *
     * @var string
     */
    public $last_name = '';

    /**
     * First Name attribute.
     *
     * @var string
     */
    public $first_name = '';

    /**
     * Email for custom url.
     *
     * @var string
     */
    public $email;

    /**
     * Customer Profile picture.
     *
     * @var string
     */
    public $picture;

    /**
     * Component mounted action.
     *
     * @param  $customer
     * @return void
     */
    public function mount($customer)
    {
        $this->customer = $customer;
        $this->user_id = $customer->id;
        $this->email = $customer->email;
        $this->last_name = $customer->last_name;
        $this->first_name = $customer->first_name;
        $this->picture = $customer->picture;
    }

    /**
     * Update Customer profile after listen to custom event.
     *
     * @return void
     */
    public function profileUpdate()
    {
        $this->email = $this->customer->email;
        $this->last_name = $this->customer->last_name;
        $this->first_name = $this->customer->first_name;
        $this->picture = $this->customer->picture;
    }

    /**
     * Update customer record in the database.
     *
     * @return void
     */
    public function store()
    {
        $this->validate($this->rules());

        (new UserRepository())->getById($this->customer->id)->update([
            'email' => $this->email,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
        ]);

        session()->flash('success', __("Customer successfully updated!"));
        $this->redirectRoute('shopper.customers.index');
    }

    /**
     * Real-time component validation.
     *
     * @param  string  $field
     * @throws \Illuminate\Validation\ValidationException
     * @return void
     */
    public function updated($field)
    {
        $this->validateOnly($field, $this->rules());
    }

    /**
     * Component validation rules.
     *
     * @return string[]
     */
    public function rules()
    {
        return [
            'email' => [
                'required',
                'max:150',
                Rule::unique(shopper_table('users'), 'email')->ignore($this->user_id),
            ],
            'last_name' => 'sometimes|required',
            'first_name' => 'sometimes|required',
        ];
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.customers.show');
    }
}