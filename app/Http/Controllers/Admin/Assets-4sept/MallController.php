<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Http\Controllers\Controller;
use App\Http\Requests\MallRequest;
use App\Models\Mall;
use App\Repositories\MallRepositoryInterface;
use Illuminate\Http\Request;

class MallController extends Controller
{
    protected MallRepositoryInterface $malls;

    public function __construct(MallRepositoryInterface $malls)
    {
        $this->malls = $malls;

        /*
        |--------------------------------------------------------------------------
        | Authentication
        |--------------------------------------------------------------------------
        */
        $this->middleware('auth');
    }

    /**
     * Display a listing of malls.
     */
    public function index(Request $request)
    {
        $malls = $this->malls->all([
            'search' => $request->get('search'),
        ]);

        return view('admin.assets.malls.index', compact('malls'));
    }

    /**
     * Show create mall form.
     */
    public function create()
    {
        return view('admin.assets.malls.create');
    }

    /**
     * Store a newly created mall.
     */
    public function store(MallRequest $request)
    {
        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Audit fields
        |--------------------------------------------------------------------------
        */
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        $mall = $this->malls->create($data);

        return redirect()
            ->route('admin.malls.show', $mall->id)
            ->with('success', 'Mall created successfully.');
    }

    /**
     * Display the specified mall.
     */
    public function show($id)
    {
        $mall = $this->malls->find($id);

        if (!$mall) {
            abort(404, 'Mall not found.');
        }

        return view('admin.assets.malls.show', compact('mall'));
    }

    /**
     * Show edit mall form.
     */
    public function edit($id)
    {
        $mall = $this->malls->find($id);

        if (!$mall) {
            abort(404, 'Mall not found.');
        }

        return view('admin.assets.malls.edit', compact('mall'));
    }

    /**
     * Update the specified mall.
     */
    public function update(MallRequest $request, $id)
    {
        $mall = $this->malls->find($id);

        if (!$mall) {
            abort(404, 'Mall not found.');
        }

        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Audit field
        |--------------------------------------------------------------------------
        */
        $data['updated_by'] = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Do not allow created_by to be changed
        |--------------------------------------------------------------------------
        */
        unset($data['created_by']);

        $this->malls->update($mall, $data);

        return redirect()
            ->route('admin.assets.malls.show', $mall->id)
            ->with('success', 'Mall updated successfully.');
    }

    /**
     * Remove the specified mall.
     */
    public function destroy($id)
    {
        $mall = $this->malls->find($id);

        if (!$mall) {
            abort(404, 'Mall not found.');
        }

        $this->malls->delete($mall);

        return redirect()
            ->route('admin.assets.malls.index')
            ->with('success', 'Mall deleted successfully.');
    }
}