<?php

namespace App\Models;

use App\Traits\ObservantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\ValueUtil;
class Cases extends Model
{
    use HasFactory, ObservantTrait;

    protected $table = 'case';

    const STATUS = [
        'pick_up' => 1, // 集荷受付
        'collected' => 2, // 集荷済
        'before_inspection' => 3, // 検品前
        'checking' => 4, // 問い合わせ確認中
        'waiting_confirm' => 5, // 個数再確認前
        'before_report' => 6, // 引取報告前
        'pick_up_report' => 7, // 引取報告入力済
        'completion_report' => 8, // 引取報告完了
        'inspected' => 9, // RP検品済
    ];
    
    protected $primaryKey = 'case_id';

    protected $keyType = 'string';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'del_flg',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'deleted_by',
        'deleted_at',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = FALSE;

    public function car() {
        return $this->hasMany(Car::class, 'case_id', 'case_id');
    }

    public function mst_scrapper() {
        return $this->belongsTo(MstScrapper::class, 'scrapper_office_code', 'office_code');
    }

    public function contract() {
        return $this->hasOne(Contract::class, 'case_id', 'case_id');
    }

    public function diffCollectRequests() {
        return $this->hasMany(DiffCollectRequest::class, 'case_id', 'case_id');
    }

    public function mismatch() {
        return $this->hasMany(Mismatch::class, 'case_id', 'case_id');
    }

    public function getExceedQtyFlgLabelAttribute()
    {
        if ($this->exceed_qty_flg === ValueUtil::constToValue('Common.allowFlg.NONE')) {
            return 'あり!';
        }

        if ($this->exceed_qty_flg === ValueUtil::constToValue('Common.allowFlg.CAN_BE')) {
            return 'なし!';
        }

        return null;
    }

    public function mst_office_sy()
    {
        return $this->belongsTo(MstOffice::class, 'sy_office_code', 'office_code');
    }

    public function pallet_case() {
        return $this->hasOne(Contract::class, 'case_id', 'case_id');
    }
}
