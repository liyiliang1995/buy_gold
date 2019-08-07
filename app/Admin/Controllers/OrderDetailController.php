<?php

namespace App\Admin\Controllers;

use App\OrderDetail;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderDetailController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '购物交易订单详情';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OrderDetail);

        $grid->column('id', __('Id'));
        $grid->column('show_type', __('单据类型'));
        $grid->column('order.order_no', __('购物订单号'));
        $grid->column('type', __('业务类型'))->display(function (){
            if ($this->type == 1) {
                return $this->gold_flow->show_type;
            } else if ($this->type == 2) {
                return $this->integral_flow->show_type;
            } else if ($this->type == 3)
                return $this->energy_flow->show_type;
        });
        $grid->column('value', __('值'))->display(function (){
            if ($this->type == 1) {
                return $this->gold_flow->gold;
            } else if ($this->type == 2) {
                return $this->integral_flow->integral;
            } else if ($this->type == 3)
                return $this->energy_flow->energy;
        });
        $grid->column('user', __('用户'))->display(function (){
            if ($this->type == 1) {
                return $this->gold_flow->member->phone ?? "系统";
            } else if ($this->type == 2) {
                return $this->integral_flow->member->phone;
            } else if ($this->type == 3)
                return $this->energy_flow->member->phone;
        });
        $grid->column('created_at', __('创建时间'));
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableView();
            $actions->disableEdit();
        });
        $grid->model()->orderBy('id', 'desc');
        $grid->model()->where('order_id',request()->input('order_id'));
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
        $show = new Show(OrderDetail::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('order_id', __('Order id'));
        $show->field('type', __('Type'));
        $show->field('flow_id', __('Flow id'));
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
        $form = new Form(new OrderDetail);

        $form->number('order_id', __('Order id'));
        $form->switch('type', __('Type'));
        $form->number('flow_id', __('Flow id'));

        return $form;
    }
}
