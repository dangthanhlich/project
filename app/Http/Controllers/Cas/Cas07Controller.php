<?php

namespace App\Http\Controllers\Cas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cas\{
    Cas071Request,
    Cas073Request
};
use App\Libs\{
    ValueUtil,
    ConfigUtil
};
use App\Repositories\{
    CaseRepository,
    TempCaseRepository,
    MismatchRepository,
    ContractRepository,
    CarRepository
};
use App\Services\Cas\Cas07Service;
use App\Models\Cases;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use PDF;
class Cas07Controller extends Controller
{
    protected $caseRepository;
    protected $tempCaseRepository;
    protected $cas07Service;
    protected $mismatchRepository;
    protected $contractRepository;

    public function __construct(CaseRepository $caseRepository, TempCaseRepository $tempCaseRepository, MismatchRepository $mismatchRepository, ContractRepository $contractRepository, Cas07Service $cas07Service)
    {
        $this->caseRepository = $caseRepository;
        $this->tempCaseRepository = $tempCaseRepository;
        $this->mismatchRepository = $mismatchRepository;
        $this->contractRepository = $contractRepository;
        $this->cas07Service   = $cas07Service; 
    }

    public function cas070()
    {
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), ['NW', 'SY']));

        $officeCode                  = auth()->user()->office_code;
        $query                       = $this->caseRepository->queryCas07($officeCode);
        $casesNotVerify              = $this->cas07Service->getByNotVerify(clone $query);
        $casesDeliveryReportMismatch = $this->cas07Service->getDeliveryReportMismatch(clone $query);
        $casesWaitingConfirmQuantity = $this->caseRepository->queryCas07($officeCode, [Cases::STATUS['waiting_confirm']])->select('case.case_no')->groupBy('case.case_no')->get();
        $tempCases                   = $this->tempCaseRepository->getByNotExistInCase($officeCode);

        return view('screens.cas.cas07.cas070', compact(
            'casesNotVerify', 'casesDeliveryReportMismatch', 'casesWaitingConfirmQuantity', 'tempCases'
        ));
    }

    public function cas071($caseNo)
    {
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), ['NW', 'SY']));
   
        $case = $this->caseRepository->findByCaseNo($caseNo);

        if (!$case) {
            abort(400);
        }

        $cars = $this->caseRepository->getCarByCaseId($case->case_id);
        $mismatchs = $this->mismatchRepository->getByCaseId($case->case_id);

        return view('screens.cas.cas07.cas071', compact('case', 'cars', 'mismatchs'));
    }

    public function handleCas071(Cas071Request $request)
    {
        try {
            $case = $this->caseRepository->findById($request->case_id);

            $this->cas07Service->updateCar($request->cars);

            if ($request->is_mismatch == 1) {
                $this->cas07Service->saveMismatch($request->mismatch_types, $request->case_id);
            }

            if ($request->is_mismatch == 2) {
                $this->mismatchRepository->deleteByCaseId($request->case_id);
            }

            return response()->json([
                'status' => true,
                'cas_072_screen' => route('case.cas-072', ['caseNo' => $case->case_no]),
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => ConfigUtil::getMessage('SERVER_ERROR'),
            ]);
        }
    }

    public function cas072($caseNo)
    {
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), ['NW', 'SY']));

        $case = $this->caseRepository->findByCaseNo($caseNo);

        if (!$case) {
            abort(400);
        }

        $cars = $this->caseRepository->getCarByCaseId($case->case_id);
        $mismatchs = $this->mismatchRepository->getByCaseId($case->case_id);

        return view('screens.cas.cas07.cas072', [
            'case' => $case,
            'cars' => $cars,
            'mismatchs' => $mismatchs,
        ]);
    }

    public function cas073($caseNo)
    {
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), ['SY']));

        $case = $this->caseRepository->findByCaseNo($caseNo);

        if (!$case) {
            abort(400);
        }

        $cars = $this->caseRepository->getCarByCaseId($case->case_id);

        return view('screens.cas.cas07.cas073', [
            'case' => $case,
            'cars' => $cars,
        ]);
    }

    public function handleCas073(Cas073Request $request, $caseId)
    {
        try {
            $case = $this->caseRepository->findById($caseId);

            if (!$case) {
                abort(400);
            }
            
            $result = $this->cas07Service->handleCas073($request->all(), $caseId);

            if (!$result) {
                return response()->json([
                    'status' => true,
                    'message' => ConfigUtil::getMessage('SERVER_ERROR'),
                ]);
            }

            return response()->json([
                'status' => true,
                'cas_070_screen' => route('case.cas-071', ['caseNo' => $case->case_no]),
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => ConfigUtil::getMessage('SERVER_ERROR'),
            ]);
        }
    }

    public function reportPDF($caseId)
    {
        $contract = $this->contractRepository->findByCaseId($caseId);
        $pdf = PDF::loadView('screens.cas.cas07.pdf', ['contract' => $contract]);
        return $pdf->download('pdf_file.pdf');
    }

    public function cas074($caseNo)
    {
        $this->checkPermission(Arr::only(ValueUtil::get('MstUser.permission'), ['SY']));

        $case = $this->caseRepository->findByCaseNo($caseNo);

        if (!$case) {
            abort(400);
        }

        $cars = $this->caseRepository->getCarByCaseId($case->case_id);
        $mismatchs = $this->mismatchRepository->getByCaseId($case->case_id);

        return view('screens.cas.cas07.cas074', [
            'case' => $case,
            'cars' => $cars,
            'mismatchs' => $mismatchs,
        ]);
    }

    public function setCaseStatus($caseId)
    {
        try {
            $mtsUserId = auth()->user()->id;
            $case = $this->caseRepository->findById($caseId);

            if (!$case) {
                abort(400);
            }

            if (request()->screen == '072') {
                $isUpdated = $this->caseRepository->updateCaseStatus($caseId, [
                    'case_status' => Cases::STATUS['waiting_confirm'],
                    'updated_by' => auth()->user()->id,
                    'updated_at' => now(),
                    'inspect_complete_time' => now(),
                    'inspect_user_id' => auth()->user()->id,
                ]);

                $checkCaseInScrapper = $this->caseRepository->findByScrapper($caseId);

                if ($checkCaseInScrapper) {
                    $contract = $this->contractRepository->findByCaseId($caseId);
                    $pdf = PDF::loadView('screens.cas.cas07.pdf', ['contract' => $contract]);
                    $fileName = $case->case_id. "_" . $case->case_no . '_' . now()->format('Ymdhhms') . '.pdf';
                    Storage::disk('s3')->put('contract/' . $fileName, $pdf->output());

                    $this->contractRepository->updateManyByCaseId([$caseId], [
                        'contract_pdf' => 'contract/' . $fileName,
                        'contract_date' => now(),
                        'updated_at' => now(),
                        'updated_by' => $mtsUserId
                    ], $mtsUserId);
                }

                return response()->json([
                    'status' => $isUpdated,
                    'message' => !$isUpdated ? ConfigUtil::getMessage('SERVER_ERROR') : null,
                    'cas_070_screen' => route('case.cas-070'),
                ]);
            }

            $isUpdated = $this->caseRepository->updateCaseStatus($caseId, [
                'case_status' => Cases::STATUS['checking'],
                'receive_complete_time' => now(),
                'receive_user_id' => $mtsUserId,
            ]);

            return response()->json([
                'status' => $isUpdated,
                'message' => !$isUpdated ? ConfigUtil::getMessage('SERVER_ERROR') : null,
                'cas_070_screen' => route('case.cas-070'),
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => ConfigUtil::getMessage('SERVER_ERROR'),
            ]);
        }
    }

    public function returnCase($caseId)
    {
        try {
            $this->caseRepository->setReturnCase($caseId, [
                'return_reason' => request()->return_reason,
                'updated_by' => auth()->user()->id,
                'return_time' => now(),
                'updated_at' => now(),
            ]);
    
            return response()->json([
                'status' => true,
                'cas_070_screen' => route('case.cas-070'),
            ]);
        }  catch(\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => ConfigUtil::getMessage('SERVER_ERROR'),
            ]);
        }
    }
}
