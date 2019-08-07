<?php

namespace App\Admin\Controllers;

use App\IntegralFlow;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class IntegralFlowController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '积分明细';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new IntegralFlow);

        $grid->column('id', __('Id'))->sortable();
        $grid->column('show_type', __('业务类型'));
        $grid->column('integral', __('积分值'));
        $grid->column('user_id', __('用户'));
        $grid->column('other', __('备注'));
        $grid->column('created_at', __('创建时间'));
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableView();
            $actions->disableEdit();
        });
        $grid->disableExport();
        $grid->disableCreateButton();
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
        $show = new Show(IntegralFlow::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('type', __('Type'));
        $show->field('integral', __('Integral'));
        $show->field('user_id', __('User id'));
        $show->field('other', __('Other'));
        $show->field('deleted_at', __('Deleted at'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new IntegralFlow);

        $form->switch('type', __('Type'));
        $form->number('integral', __('Integral'));
        $form->number('user_id', __('User id'));
        $form->text('other', __('Other'));

        return $form;
    }
}
