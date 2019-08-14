<?php

namespace App\Admin\Controllers;

use App\PhoneBuyGold;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PhonebuyController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '手机充值记录';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PhoneBuyGold);

        $grid->column('id', __('Id'));
        $grid->column('member.phone', __('挂单用户'));
        $grid->column('gold', __('价值总金币'));
        $grid->column('price', __('当前币值'));
        $grid->column('sum_price', __('充值金额'));
        $grid->column('status', __('状态'))->display(function ($status){
            if ($status == 1){
                return '已完成';
            }else{
                return '未完成';
            }
        });
        $grid->column('seller_id', __('抢单用户'));
        $grid->column('created_at', __('挂单时间'));
        $grid->column('updated_at', __('更新时间'));
        $grid->disableCreateButton();
        $grid->disableActions();
        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('member.phone', '挂单用户');
        });
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
        $show = new Show(PhoneBuyGold::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('gold', __('Gold'));
        $show->field('price', __('Price'));
        $show->field('sum_price', __('Sum price'));
        $show->field('status', __('Status'));
        $show->field('seller_id', __('Seller id'));
        $show->field('is_show', __('Is show'));
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
        $form = new Form(new PhoneBuyGold);

        $form->number('user_id', __('User id'));
        $form->decimal('gold', __('Gold'))->default(0.00);
        $form->decimal('price', __('Price'))->default(0.00);
        $form->decimal('sum_price', __('Sum price'))->default(0.00);
        $form->switch('status', __('Status'));
        $form->number('seller_id', __('Seller id'));
        $form->switch('is_show', __('Is show'))->default(1);

        return $form;
    }
}
