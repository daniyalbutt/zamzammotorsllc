<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CustomerPortalController extends Controller
{
    /**
     * Get the authenticated customer
     */
    private function getCustomer()
    {
        $user = Auth::user();
        $customer = Customer::where('user_id', $user->id)->first();

        if (!$customer) {
            abort(404, 'Customer profile not found.');
        }

        return $customer;
    }

    /**
     * My Profile page with tabs
     */
    public function myProfile()
    {
        $user = Auth::user();
        $customer = $this->getCustomer();

        return view('customer-portal.my-profile', compact('user', 'customer'));
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    /**
     * Update account information
     */
    public function updateAccountInfo(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'Account information updated successfully!');
    }

    /**
     * Update contact information
     */
    public function updateContactInfo(Request $request)
    {
        $user = Auth::user();
        $customer = $this->getCustomer();

        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $customer->update($validated);

        // Update user phone if provided
        if ($request->filled('phone')) {
            $user->update(['phone' => $request->phone]);
        }

        return redirect()->back()->with('success', 'Contact information updated successfully!');
    }

    /**
     * My Account Info page
     */
    public function myAccountInfo()
    {
        $user = Auth::user();
        $customer = $this->getCustomer();
        $customer->load(['assignedAgent', 'invoices']);

        return view('customer-portal.my-account-info', compact('user', 'customer'));
    }

    /**
     * My Consignee Details page
     */
    public function myConsigneeDetails()
    {
        $user = Auth::user();
        $customer = $this->getCustomer();

        return view('customer-portal.my-consignee-details', compact('user', 'customer'));
    }

    /**
     * Update consignee details
     */
    public function updateConsigneeDetails(Request $request)
    {
        $customer = $this->getCustomer();

        $validated = $request->validate([
            'consignee_name' => 'nullable|string|max:255',
            'consignee_phone' => 'nullable|string|max:20',
            'consignee_email' => 'nullable|email|max:255',
            'consignee_address' => 'nullable|string|max:500',
            'consignee_city' => 'nullable|string|max:100',
            'consignee_country' => 'nullable|string|max:100',
            'consignee_postal_code' => 'nullable|string|max:20',
        ]);

        // Store as JSON in a notes field or create separate fields
        // For now, we'll use the address field with JSON encoding
        $customer->update([
            'consignee_details' => json_encode($validated)
        ]);

        return redirect()->back()->with('success', 'Consignee details updated successfully!');
    }

    /**
     * My Favorites page
     */
    public function myFavorites()
    {
        $user = Auth::user();

        // Get favorite vehicle IDs from session or database
        $favoriteIds = session('favorite_vehicles', []);
        $favorites = Vehicle::whereIn('id', $favoriteIds)->get();

        return view('customer-portal.my-favorites', compact('favorites'));
    }

    /**
     * Add vehicle to favorites
     */
    public function addToFavorites(Vehicle $vehicle)
    {
        $favorites = session('favorite_vehicles', []);

        if (!in_array($vehicle->id, $favorites)) {
            $favorites[] = $vehicle->id;
            session(['favorite_vehicles' => $favorites]);
        }

        return redirect()->back()->with('success', 'Vehicle added to favorites!');
    }

    /**
     * Remove vehicle from favorites
     */
    public function removeFromFavorites(Vehicle $vehicle)
    {
        $favorites = session('favorite_vehicles', []);
        $favorites = array_diff($favorites, [$vehicle->id]);
        session(['favorite_vehicles' => $favorites]);

        return redirect()->back()->with('success', 'Vehicle removed from favorites!');
    }

    /**
     * Reserved Vehicles page
     */
    public function reservedVehicles()
    {
        $customer = $this->getCustomer();

        // Get vehicles with 'Reserved' status linked to customer's invoices
        $reservedVehicles = Vehicle::whereHas('invoices', function ($query) use ($customer) {
            $query->where('customer_id', $customer->id)
                  ->where('status', '!=', 'Paid');
        })->where('status', 'Reserved')->get();

        return view('customer-portal.reserved-vehicles', compact('reservedVehicles'));
    }

    /**
     * Purchased Vehicles page
     */
    public function purchasedVehicles()
    {
        $customer = $this->getCustomer();

        // Get vehicles with 'Sold Out' status linked to customer's paid invoices
        $purchasedVehicles = Vehicle::whereHas('invoices', function ($query) use ($customer) {
            $query->where('customer_id', $customer->id)
                  ->where('status', 'Paid');
        })->where('status', 'Sold Out')->with('invoices')->get();

        return view('customer-portal.purchased-vehicles', compact('purchasedVehicles'));
    }
}
