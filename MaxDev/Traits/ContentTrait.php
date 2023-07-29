<?php
/**
 * @Author Mostafa Naguib
 * @Copyright Maximum Develop
 * @FileCreated 11/19/20 2:27 PM
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace MaxDev\Traits;

use MaxDev\Modules\Administrators\Requests\ContentRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\View\Factory;
use MaxDev\Rules\FileUploadExtension;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use MaxDev\Models\Content;
use MaxDev\Models\Tag;
use Exception;

trait ContentTrait
{
    public int $imageUploadMaxHeight = 1000;
    public int $imageUploadMaxWidth = 1000;

    /**
     * Display a listing of the resource.
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request)
    {
        $this->viewData['title']            = __($this->resourceName);
        $this->viewData['tableColumns']     = [__('ID'), __('title'), __('link'), __('Last updated'), __('Action')];
        $this->viewData['Cols']             = ['id', 'title', 'link', 'last_updated', 'action'];
        $this->viewData['buttons']          = $this->template->generateButtons($this->routePrefix, null, 'index');

        if ($request->isDataTable) {
            $eloquentData = Content::select(['id', 'title', 'slug', 'updated_at']);

            if ($this->type) {
                $eloquentData->where('type', $this->type);
            } else {
                $eloquentData->Page();
            }

            // Filtering
            if ($request->input('order.0.column') !== false && isset($this->viewData['Cols'][$request->input('order.0.column')])) {
                $column_name=$this->viewData['Cols'][$request->input('order.0.column')];
                switch ($column_name) {
                    case 'name':
                        $by='title';
                        break;
                    case 'id':
                        break;
                }
                if (isset($by)) {
                    $eloquentData->orderBy($by, $request->input('order.0.dir'));
                }
            }

            if ($request->created_at1 || $request->created_at2) {
                WhereBetween($eloquentData, $request->created_at1, $request->created_at2);
            }

            // Filtering
            if ($request->id) {
                $eloquentData->where('id', $request->id);
            }

            if ($request->title) {
                foreach ($request->title as $lang=>$string) {
                    if ($string) {
                        $eloquentData->where("title->$lang", 'LIKE', '%'.$string.'%');
                    }
                }
            }

            if ($request->input('search.value')) {
                $name=$request->input('search.value');
                $eloquentData->where('title', 'LIKE', '%'.$name.'%');
            }


            // DataTables
            return DataTables::of($eloquentData)
                ->rawColumns(['title', 'action'])
                ->addColumn('id', function ($data) {
                    return $data->id;
                })
                ->addColumn('title', function ($data) {
                    $titles = '';
                    foreach (config('app.locales') as $locale) {
                        $titles .= '<b>' . strtoupper($locale) . '</b>: ' . $data->getTranslation('title', $locale) . '<hr>';
                    }
                    return $titles;
                })
                ->addColumn('link', function ($data) {
                    return $data->slug;
                })
                ->addColumn('last_updated', function ($data) {
                    return $data->updated_at->diffForHumans();
                })
                ->addColumn('action', function ($data) {
                    return $this->template->generateRowDropDown([
                        [
                            'route' =>  $this->routePrefix . 'show',
                            'url'   =>  route($this->routePrefix . 'show', $data->id),
                            'class' =>  'fa fa-eye',
                            'text'  =>  __('View')
                        ],
                        [
                            'route' =>  $this->routePrefix . 'edit',
                            'url'   =>  route($this->routePrefix . 'edit', $data->id) . '?lang=ar',
                            'class' =>  'fa fa-edit',
                            'text'  =>  __('Edit') . '(' . __('Ar') . ')'
                        ],
                        [
                            'route' =>  $this->routePrefix . 'edit',
                            'url'   =>  route($this->routePrefix . 'edit', $data->id) . '?lang=en',
                            'class' =>  'fa fa-edit',
                            'text'  =>  __('Edit') . '(' . __('En') . ')'
                        ],
                        [
                            'route' =>  $this->routePrefix . 'destroy',
                            'onclick'   =>  'deleteRecord(\'' . route($this->routePrefix . 'destroy', $data->id) . '\',this)',
                            'class' =>  'fa fa-trash',
                            'text'  =>  __('Delete')
                        ]
                    ]);
                })
                ->make(true);
        }
        return $this->view($this->viewDir . 'index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Application|Factory|View
     */
    public function create()
    {
        $this->viewData['title']    = __('New') . ' ' . __($this->resourceName);
        $this->viewData['submit']   = __('Add') . ' ' . __($this->resourceName);
        $this->viewData['type']   = $this->type;
        $this->viewData['resourceName']   = __($this->resourceName);

        return $this->view($this->viewDir . 'create');
    }

    /**
     * Store a newly created resource in storage.
     * @param ContentRequest $request
     * @return RedirectResponse
     */
    public function store(ContentRequest $request)
    {
        $Content = $this->UpdateTranslations(new Content(), $request->validated());
        $Content->slug = Str::slug($request->title[$request->language]);
        $Content->type = $this->type;
        $Content->author = auth('admin')->user()['fullname'];
        $Content->save();

        if(isset($request->tag) && count($request->tag) > 0) {
            $tags_ids = [];
            foreach($request->tag as $tag_from_request)  {
                $tag_name = ['name' => $tag_from_request];
                $tag = Tag::updateOrCreate($tag_name, $tag_name);
                $tags_ids[] = $tag->id;
            }
            $Content->tags()->syncWithPivotValues($tags_ids, ['lang' => $request->language]);
        }

        if ($Content) {
            return $this->WithSuccess($this->routePrefix . 'index', __('Content has been successfully added'));
        } else {
            return $this->WithError($this->routePrefix . 'index', __('Content could not be added'));
        }
    }

    /**
     * Display the specified resource.
     * @param Content $page
     * @return Application|Factory|View
     */
    public function show(Content $page)
    {
        $this->viewData['title']    = __('View') . ' ' . __($this->resourceName);
        $this->viewData['row']      = $page;
        $this->viewData['buttons']  = $this->template->generateButtons($this->routePrefix, $page, 'show');

        return $this->view($this->viewDir . 'show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param Content $page
     * @return Application|Factory|View
     */
    public function edit(Content $page)
    {
        if (!in_array(request('lang'), config('app.locales'))) {
            redirect($this->routePrefix . 'index');
        }

        $this->viewData['title']    = __('Edit') . ' ' . __($this->resourceName) . ' ' . $page->getTranslation('title', app()->getLocale());
        $this->viewData['resourceName']   = __($this->resourceName);
        $this->viewData['row']      = $page;
        $this->viewData['buttons']  = $this->template->generateButtons($this->routePrefix, $page, 'edit');
        $this->viewData['submit']   = __('Save') . ' ' . __($this->resourceName);
        $this->viewData['type']   = $this->type;

        if(isset($page->tags) && count($page->tags) > 0) {
            $result_tags = '';
            foreach($page->tags->toArray() as $key => $tag) {
                if(count($page->tags->toArray()) > $key + 1) {
                    $result_tags .= $tag['name'] . ', ';
                } else {
                    $result_tags .= $tag['name'];
                }
            }
            $this->viewData['tags'] = $result_tags;
        }


        return $this->view($this->viewDir . 'create');
    }

    /**
     * Update the specified resource in storage.
     * @param ContentRequest $request
     * @param Content $page
     * @return RedirectResponse
     */
    public function update(ContentRequest $request, Content $page)
    {
        $page = $this->UpdateTranslations($page, $request->validated());
        $page->save();

        if(isset($request->tag) && count($request->tag) > 0) {
            $tags_ids = [];
            foreach($request->tag as $tag_from_request)  {
                $tag_name = ['name' => $tag_from_request];
                $tag = Tag::updateOrCreate($tag_name, $tag_name);
                $tags_ids[] = $tag->id;
            }
            $page->tags()->syncWithPivotValues($tags_ids, ['lang' => $request->language]);
        }

        if ($page) {
            return $this->WithSuccess($this->routePrefix . 'index', __('Content has been successfully edited'));
        } else {
            return $this->WithError($this->routePrefix . 'index', __('Content could not be edited'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param Content $page
     * @return JsonResponse | RedirectResponse
     */
    public function destroy(Content $page)
    {
        $status = $page->delete();
        if (request()->ajax()) {
            return response()->json([
                'type'  => (($status) ? 'success' : 'error'),
                'msg'   => (($status) ? __('Content successfully deleted') : __('Can not delete this content')),
            ]);
        } else {
            if ($status) {
                return $this->WithSuccess($this->routePrefix . 'index', __('Content successfully deleted'));
            } else {
                return $this->WithError($this->routePrefix . 'index', __('Can not delete published page'));
            }
        }
    }

    public function upload(Request $request)
    {
        $RequestedData = $request->only('image');
        $validator = Validator::make(
            $RequestedData,
            [
                'image' => [
                    'required',
                    'image',
                    new FileUploadExtension(['jpg', 'png', 'jpeg', 'gif']),
                ]
            ]
        );

        if ($validator->errors()->any()) {
            return ['status'=>false,'msg'=>__('Something is wrong'),'errors'=>$validator->errors()];
        }

        if ($request->file('image')) {
            $image = upload_file($request->file('image'), $this->uploadDir, true, false, ['width' => $this->imageUploadMaxWidth, 'height' => $this->imageUploadMaxHeight]);
        }

        if (app()->environment('local')) {
            return asset('storage/' . $image);
        } else {
            return app_asset($image);
        }
    }

    public function UpdateTranslations($model, $data)
    {
        $model->setTranslation('title', $data['language'], $data['title'][$data['language']]);
        $model->setTranslation('keywords', $data['language'], $data['keywords'][$data['language']]);
        $model->setTranslation('description', $data['language'], $data['description'][$data['language']]);
        $model->setTranslation('html', $data['language'], $data['html'][$data['language']]);

        return $model;
    }
}
