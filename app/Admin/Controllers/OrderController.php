<?php

namespace App\Admin\Controllers;

use App\Order;
use App\OrderItem;
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
    protected $title = '购物订单';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order);

        $grid->column('id', __('Id'))->sortable();
        $grid->column('order_no', __('订单号'));
        $grid->column('member.phone', __('用户'));
        $grid->column('pay_gold', __('支付金币'));
        $grid->column('amount', __('价值金额'));
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
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableView();
            $url = route("order-details.index", ['order_id' => $actions->getKey()]);
            $actions->append('<a href="' . $url . '">流水详情</a>');
        });
        $grid->disableExport();
        $grid->disableRowSelector();
        // 去掉默认的id过滤器
        $grid->disableIdFilter();
        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('order_no', '订单号');
            $filter->like('member.phone', '用户手机');
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
        $show->field('pay_gold', __('支付金币'));
        $show->field('amount', __('价值金额'));
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

        $form->text('order_no', __('订单号'))->disable();
       // $form->number('user_id', __('用户id'));
        $form->decimal('pay_gold', __('支付金币'))->default(0.00)->disable();
        $form->decimal('amount', __('价值金额'))->default(0.00)->disable();
        $form->text('express', __('快递单号'));
        $form->text('other', __('买家留言'));
        $form->select('is_send', __('是否发货'))->options([0 => '否', 1 => '是']);
        // 去掉`查看`checkbox
        $form->disableViewCheck();
        // 去掉`继续编辑`checkbox
        $form->disableEditingCheck();
        // 去掉`继续创建`checkbox
        $form->disableCreatingCheck();
        return $form;
    }

    public function update($id)
    {
        //dd(request()->input());
        $oOrderModel = new Order();
        $oOrderItemModel = new OrderItem();
        $order_no = $oOrderModel->find($id)->order_no;
        $oOrderItemModel->where('order_no',$order_no)->update(['is_send'=>request()->input('is_send') ?? 0]);
//        oOrderItemModel->where();
        return $this->form()->update($id);
    }
}
