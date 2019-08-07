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
    protected $title = 'App\GoldFlow';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new GoldFlow);

        $grid->column('id', __('Id'));
        $grid->column('is_income', __('收入支出'))->display(function ($is_income){
            if ($is_income == 1){
                return '收入';
            }else{
                return '支出';
            }
        });
        $grid->column('type', __('业务类型'))->display(function ($type){
            switch ($type){
                case 1:
                    return '用户消费';
                case 2:
                    return '用户出售';
                case 3:
                    return '用户求购';
                case 4:
                    return '领取金币';
                case 5:
                    return '返回金币池';
                case 6:
                    return '代理注册扣除';
            }
        });
        $grid->column('user_id', __('用户ID'));
        $grid->column('gold', __('金币数量'));
        $grid->column('other', __('备注'));
        //$grid->column('is_statistical', __('Is statistical'));
        //$grid->column('deleted_at', __('Deleted at'));
        $grid->column('created_at', __('时间'));
        //$grid->column('updated_at', __('Updated at'));

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
