<?php

namespace App\Admin\Controllers;

use App\BuyGold;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BuyGoldController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '金币交易';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new BuyGold);

        $grid->column('id', __('Id'))->sortable();
        $grid->column('member.phone', __('买家'));
        $grid->column('gold', __('金币'));
        $grid->column('price', __('人民币单价'));
        $grid->column('sum_price', __('人民币总价'));
        $grid->column('buy_gold_status', __('状态'));
        $grid->column('seller.phone', __('卖家'));
        $grid->column('created_at', __('创建时间'));

        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableView();
            $actions->disableEdit();
            $url = route("buy-gold-details.index", ['buy_gold_id' => $actions->getKey()]);
            $actions->append('<a href="' . $url . '">流水详情</a>');
        });
        $grid->disableExport();
        $grid->disableCreateButton();
        $grid->model()->orderBy('id', 'desc');
        $grid->disableRowSelector();

        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('member.phone', '买家手机');
            $filter->like('seller.phone', '卖家手机');
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
        $show = new Show(BuyGold::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('gold', __('Gold'));
        $show->field('price', __('Price'));
        $show->field('sum_price', __('Sum price'));
        $show->field('status', __('Status'));
        $show->field('deleted_at', __('Deleted at'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('is_show', __('Is show'));
        $show->field('seller_id', __('Seller id'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new BuyGold);

        $form->number('user_id', __('User id'));
        $form->decimal('gold', __('Gold'))->default(0.00);
        $form->decimal('price', __('Price'))->default(0.00);
        $form->decimal('sum_price', __('Sum price'))->default(0.00);
        $form->switch('status', __('Status'));
        $form->switch('is_show', __('Is show'))->default(1);
        $form->number('seller_id', __('Seller id'));

        return $form;
    }
}
