<?php

namespace App\Admin\Controllers;

use App\GoldFlow;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class GoldflowController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '金币流水';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new GoldFlow);

        $grid->column('id', __('Id'))->sortable();
        $grid->column('is_income', __('收入支出'))->display(function ($is_income){
            return $is_income ? "收入" : '支出';
        });
        $grid->column('show_type', __('业务类型'));
        $grid->column('user', __('用户'))->display(function (){
            return $this->member->phone ?? "金币池";
        });
        $grid->column('gold', __('金币数量'));
        $grid->column('other', __('备注'));
        //$grid->column('is_statistical', __('Is statistical'));
        //$grid->column('deleted_at', __('Deleted at'));
        $grid->column('created_at', __('时间'));
        //$grid->column('updated_at', __('Updated at'));
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
        $grid->disableRowSelector();
        $grid->disableActions();
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
        $show = new Show(GoldFlow::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('is_income', __('Is income'));
        $show->field('type', __('Type'));
        $show->field('user_id', __('User id'));
        $show->field('gold', __('Gold'));
        $show->field('other', __('Other'));
        $show->field('is_statistical', __('Is statistical'));
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
        $form = new Form(new GoldFlow);

        $form->switch('is_income', __('Is income'));
        $form->switch('type', __('Type'));
        $form->number('user_id', __('User id'));
        $form->decimal('gold', __('Gold'))->default(0.00);
        $form->text('other', __('Other'));
        $form->switch('is_statistical', __('Is statistical'));

        return $form;
    }
}
