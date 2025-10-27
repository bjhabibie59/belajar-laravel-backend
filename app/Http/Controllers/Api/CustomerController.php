<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Services\CustomerService;
use App\Helpers\ResponseHelper;


class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index()
    {
        try {
            $customers = $this->customerService->getAllCustomers();
            return ResponseHelper::success(
                CustomerResource::collection($customers),
                'Berhasil mengambil data pelanggan'
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

    public function store(CustomerRequest $request)
    {
        try {
            $customer = $this->customerService->createCustomer($request->validated());
            return ResponseHelper::success(
                new CustomerResource($customer),
                'Berhasil menambahkan pelanggan',
                201
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $customer = $this->customerService->getCustomerById($id);
            return ResponseHelper::success(
                new CustomerResource($customer->load('orders')),
                'Berhasil mengambil detail pelanggan'
            );
        } catch (\Exception $e) {
            return ResponseHelper::notFound();
        }
    }

    public function update(CustomerRequest $request, $id)
    {
        try {
            $customer = $this->customerService->updateCustomer($id, $request->validated());
            return ResponseHelper::success(
                new CustomerResource($customer),
                'Berhasil mengupdate pelanggan'
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->customerService->deleteCustomer($id);
            return ResponseHelper::success(null, 'Berhasil menghapus pelanggan');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

    public function topCustomers()
    {
        try {
            $customers = $this->customerService->getTopCustomers();
            return ResponseHelper::success(
                CustomerResource::collection($customers),
                'Berhasil mengambil top pelanggan'
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

}
