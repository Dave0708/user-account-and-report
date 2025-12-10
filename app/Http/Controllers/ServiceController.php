<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::paginate(15);
        return view('services.index', compact('services'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_name' => 'required|string|max:255|unique:services',
            'duration' => 'required|date_format:H:i:s',
        ]);

        Service::create($validated);
        return redirect()->route('services.index')->with('success', 'Service created successfully');
    }

    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'service_name' => 'required|string|max:255|unique:services,service_name,' . $service->id,
            'duration' => 'required|date_format:H:i:s',
        ]);

        $service->update($validated);
        return redirect()->route('services.index')->with('success', 'Service updated successfully');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Service deleted successfully');
    }
}
