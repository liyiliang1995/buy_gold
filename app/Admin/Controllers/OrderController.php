<?php

namespace App\Admin\Controllers;

use App\Order;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Order';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order);

        $grid->column('id', __('Id'));
        $grid->column('order_no', __('订单号'));
        $grid->column('user_id', __('用户id'));
        $grid->column('pay_gold', __('支付金额'));
        $grid->column('amount', __('订单金额'));
        $grid->column('express', __('快递单号'));
        //$grid->column('deleted_at', __('Deleted at'));
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('更新时间'));
        $grid->column('other', __('买家留言'));
        $grid->column('is_send', __('状态'))->display(function ($is_send){
            if ($is_send == 1){
                return '已发货';
            }else{
                return '未发货';
            }
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
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('order_no', __('订单号'));
        $show->field('user_id', __('用户id'));
        $show->field('pay_gold', __('支付金额'));
        $show->field('amount', __('订单金额'));
        $show->field('express', __('快递单号'));
       // $show->field('deleted_at', __('Deleted at'));
        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('更新时间'));
        $show->field('other', __('买家留言'));
        $show->field('is_send', __('是否发货'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order);

        $form->text('order_no', __('订单号'));
       // $form->number('user_id', __('用户id'));
        $form->decimal('pay_gold', __('支付金额'))->default(0.00);
        $form->decimal('amount', __('订单金额'))->default(0.00);
        $form->text('express', __('快递单号'));
        $form->text('other', __('买家留言'));
        $form->switch('is_send', __('是否发货'));

        return $form;
    }
}
