<?php

namespace App\Models\Auth\User;

use App\Models\Auth\User\Traits\Ables\Protectable;
use App\Models\Auth\User\Traits\Attributes\UserAttributes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Auth\User\Traits\Ables\Rolable;
use App\Models\Auth\User\Traits\Scopes\UserScopes;
use App\Models\Auth\User\Traits\Relations\UserRelations;
use Kyslik\ColumnSortable\Sortable;

/**
 * App\Models\Auth\User\User
 *
 * @property int $id
 * @property int $employee_id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property int $active
 * @property string $confirmation_code
 * @property bool $confirmed
 * @property string $remember_token
 * @property int $status_id
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * @property string $deleted_at
 * @property-read mixed $avatar
 * @property-read mixed $licensee_name
 * @property-read mixed $licensee_number
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Protection\ProtectionShopToken[] $protectionShopTokens
 * @property-read \App\Models\Protection\ProtectionValidation $protectionValidation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Auth\User\SocialAccount[] $providers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Auth\Role\Role[] $roles
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Auth\User\User sortable($defaultSortParameters = null)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Auth\User\User whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Auth\User\User whereConfirmationCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Auth\User\User whereConfirmed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Auth\User\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Auth\User\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Auth\User\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Auth\User\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Auth\User\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Auth\User\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Auth\User\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Auth\User\User whereRole($role)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Auth\User\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Rolable,
        UserAttributes,
        UserScopes,
        UserRelations,
        Notifiable,
        SoftDeletes,
        Sortable,
        Protectable;

    public $sortable = ['username', 'created_at', 'updated_at'];

    protected $casts = [
        'employee_id' => 'int',
        'active' => 'int',
        'confirmed' => 'bool',
        'status_id' => 'int',
        'created_by' => 'int',
        'updated_by' => 'int'
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
        'confirmation_code',
        'confirmed',
        'username',
        'status_id',
        'created_by',
        'updated_by'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee::class);
    }

    public function status()
    {
        return $this->belongsTo(\App\Models\Status::class);
    }

    public function approval_payment_requests()
    {
        return $this->hasMany(\App\Models\ApprovalPaymentRequest::class);
    }

    public function approval_purchase_requests()
    {
        return $this->hasMany(\App\Models\ApprovalPurchaseRequest::class);
    }

    public function approval_rules()
    {
        return $this->hasMany(\App\Models\ApprovalRule::class);
    }

    public function delivery_order_headers()
    {
        return $this->hasMany(\App\Models\DeliveryOrderHeader::class, 'confirm_by');
    }

    public function departments()
    {
        return $this->hasMany(\App\Models\Department::class, 'updated_by');
    }

    public function employees()
    {
        return $this->hasMany(\App\Models\Employee::class, 'updated_by');
    }

    public function interchanges()
    {
        return $this->hasMany(\App\Models\Interchange::class, 'created_by');
    }

    public function issued_docket_headers()
    {
        return $this->hasMany(\App\Models\IssuedDocketHeader::class, 'updated_by');
    }

    public function item_mutations()
    {
        return $this->hasMany(\App\Models\ItemMutation::class, 'updated_by');
    }

    public function item_receipt_headers()
    {
        return $this->hasMany(\App\Models\ItemReceiptHeader::class, 'updated_by');
    }

    public function item_stocks()
    {
        return $this->hasMany(\App\Models\ItemStock::class, 'updated_by');
    }

    public function items()
    {
        return $this->hasMany(\App\Models\Item::class, 'updated_by');
    }

    public function machineries()
    {
        return $this->hasMany(\App\Models\Machinery::class, 'updated_by');
    }

    public function payment_requests()
    {
        return $this->hasMany(\App\Models\PaymentRequest::class, 'updated_by');
    }

    public function permission_documents()
    {
        return $this->hasMany(\App\Models\PermissionDocument::class, 'updated_by');
    }

    public function purchase_invoice_headers()
    {
        return $this->hasMany(\App\Models\PurchaseInvoiceHeader::class, 'updated_by');
    }

    public function purchase_order_headers()
    {
        return $this->hasMany(\App\Models\PurchaseOrderHeader::class, 'updated_by');
    }

    public function purchase_request_headers()
    {
        return $this->hasMany(\App\Models\PurchaseRequestHeader::class, 'updated_by');
    }

    public function quotation_headers()
    {
        return $this->hasMany(\App\Models\QuotationHeader::class, 'updated_by');
    }

    public function serials()
    {
        return $this->hasMany(\App\Models\Serial::class, 'updated_by');
    }

    public function sites()
    {
        return $this->hasMany(\App\Models\Site::class, 'updated_by');
    }

    public function stock_adjustments()
    {
        return $this->hasMany(\App\Models\StockAdjustment::class, 'updated_by');
    }

    public function stock_ins()
    {
        return $this->hasMany(\App\Models\StockIn::class, 'updated_by');
    }

    public function suppliers()
    {
        return $this->hasMany(\App\Models\Supplier::class, 'updated_by');
    }
}
