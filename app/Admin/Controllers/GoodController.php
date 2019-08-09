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
        $grid->column('id', __('Id'))->sortable();
        $grid->column('name', __('名称'));
        $grid->column('unit', __('单位'));
        //$grid->column('list_img', __('商品图片'))->image()->width(10);
        $grid->column('amount', __('价格'));
        $grid->column('created_at', __('创建时间'));
        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->disableFilter();
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
        $show->field('list_img', __('商品图片'));
        $show->field('amount', __('价格'));
        $show->field('created_at', __('创建时间'));
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Good);
        $form->text('name', __('名称'));
        $form->decimal('amount', __('价格'))->default(0.00);
        $form->text('unit', __('单位'));
        $form->image('list_img', __('缩略图(320*320)'));
        $form->hasMany('goodsimgs', __('轮播图(640*640)'),function(Form\NestedForm $form){
            $form->image('img',__('轮播图'));
        });
        $form->kindeditor('describe', __('描述'));
        // 去掉`查看`checkbox
        $form->disableViewCheck();
        // 去掉`继续编辑`checkbox
        $form->disableEditingCheck();
        // 去掉`继续创建`checkbox
        $form->disableCreatingCheck();
        return $form;
    }
}
