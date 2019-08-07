<?php

namespace App\Admin\Controllers;

use App\EnergyFlow;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class EnergyController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '能量值明细';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new EnergyFlow);

        $grid->column('id', __('Id'))->sortable();;
        $grid->column('show_type', __('业务类型'));
        $grid->column('energy', __('能量值'));
        $grid->column('member.phone', __('用户'));
        $grid->column('other', __('备注'));
        $grid->column('created_at', __('创建时间'));
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableView();
            $actions->disableEdit();
        });
        $grid->model()->orderBy('id', 'desc');
        if (!empty(request()->input('user_id')))
            $grid->model()->where('user_id',request()->input('user_id'));
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
        $show = new Show(EnergyFlow::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('type', __('Type'));
        $show->field('energy', __('Energy'));
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
        $form = new Form(new EnergyFlow);

        $form->switch('type', __('Type'));
        $form->number('energy', __('Energy'));
        $form->number('user_id', __('User id'));
        $form->text('other', __('Other'));

        return $form;
    }
}
