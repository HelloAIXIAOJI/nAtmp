<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DomainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created domain.
     */
    public function store(Request $request, Website $website)
    {
        $this->authorize('update', $website);

        $validator = Validator::make($request->all(), [
            'domain' => 'required|string|max:255|unique:domains,domain',
            'is_primary' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // If setting as primary, unset other primary domains
        if ($request->is_primary) {
            $website->domains()->update(['is_primary' => false]);
        }

        $domain = $website->domains()->create([
            'domain' => $request->domain,
            'is_primary' => $request->is_primary ?? false,
        ]);

        return redirect()->route('websites.show', $website)
            ->with('success', '域名添加成功！');
    }

    /**
     * Update the specified domain.
     */
    public function update(Request $request, Website $website, Domain $domain)
    {
        $this->authorize('update', $website);

        if ($domain->website_id !== $website->id) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'domain' => 'required|string|max:255|unique:domains,domain,' . $domain->id,
            'is_primary' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // If setting as primary, unset other primary domains
        if ($request->is_primary) {
            $website->domains()->where('id', '!=', $domain->id)->update(['is_primary' => false]);
        }

        $domain->update([
            'domain' => $request->domain,
            'is_primary' => $request->is_primary ?? false,
        ]);

        return redirect()->route('websites.show', $website)
            ->with('success', '域名更新成功！');
    }

    /**
     * Remove the specified domain.
     */
    public function destroy(Website $website, Domain $domain)
    {
        $this->authorize('update', $website);

        if ($domain->website_id !== $website->id) {
            abort(403);
        }

        $domain->delete();

        return redirect()->route('websites.show', $website)
            ->with('success', '域名已删除！');
    }
}
