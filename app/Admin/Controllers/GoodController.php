<?php

namespace App\Admin\Controllers;

use App\Good;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class GoodController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '商品管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Good);

        $grid->column('id', __('Id'));
        $grid->column('name', __('名称'));
        $grid->column('describe', __('描述'));
        $grid->column('list_img', __('列表图片'));
        $grid->column('amount', __('价格'));
//        $grid->column('deleted_at', __('Deleted at'));
        $grid->column('created_at', __('创建时间'));
//        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Good::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('名称'));
        $show->field('describe', __('描述'));
        $show->field('list_img', __('列表图片'));
        $show->field('amount', __('价格'));
       // $show->field('deleted_at', __('Deleted at'));
        $show->field('created_at', __('创建时间'));
        //$show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
//        dd(request()->input());
        $form = new Form(new Good);

        $form->text('name', __('名称'));
        $form->kindeditor('describe', __('描述'));
        $form->fileupload('list_img', __('图片'));
        $form->hasMany('goodsimgs',function(Form\NestedForm $form){
            $form->image('img');
        });
        //$form->multipleImage('img')->sortable();
        $form->decimal('amount', __('Amount'))->default(0.00);
        return $form;
    }
}
