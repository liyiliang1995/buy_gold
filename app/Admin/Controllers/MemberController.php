<?php

namespace App\Admin\Controllers;

use App\Member;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class MemberController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '会员';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Member);

        $grid->column('id', __('Id'));
        $grid->column('name', __('姓名'));
        $grid->column('phone', __('手机号码'));
        $grid->column('phone2', __('联系手机号'));
        $grid->column('gold', __('金币'));
        $grid->column('energy', __('能量'));
        $grid->column('integral', __('积分值'));
        $grid->column('parent_user_id', __('上级用户'));
        $grid->column('child_user_num', __('代理下级个数'));
        $grid->column('wechat', __('微信'));
        $grid->column('status', __('状态'));
        $grid->column('is_admin', __('是否股东'));
        $grid->column('ship_address', __('收获地址'));
//        $grid->column('deleted_at', __('Deleted at'));
        $grid->column('created_at', __('创建时间'));
//        $grid->column('updated_at', __('Updated at'));
//        $grid->column('password', __('Password'));

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
        $show = new Show(Member::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('phone', __('Phone'));
        $show->field('phone2', __('Phone2'));
        $show->field('gold', __('Gold'));
        $show->field('energy', __('Energy'));
        $show->field('integral', __('Integral'));
        $show->field('parent_user_id', __('Parent user id'));
        $show->field('child_user_num', __('Child user num'));
        $show->field('wechat', __('Wechat'));
        $show->field('status', __('Status'));
        $show->field('is_admin', __('Is admin'));
        $show->field('ship_address', __('Ship address'));
        $show->field('deleted_at', __('Deleted at'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('password', __('Password'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Member);

        $form->text('name', __('名称'));
        $form->mobile('phone', __('手机号码'));
        $form->text('phone2', __('联系手机'));
        $form->decimal('gold', __('金币'))->default(0.00);
        $form->decimal('energy', __('能量值'))->default(0.00);
        $form->decimal('integral', __('积分值'))->default(0.00);
//        $form->number('parent_user_id', __('Parent user id'));
//        $form->number('child_user_num', __('Child user num'));
        $form->text('wechat', __('微信'));
        $form->select('is_admin', __('是否股东'))->options([0 => '否',1 => '是'])->default(0);
        $form->text('ship_address', __('收获地址'));
        $form->password('password', __('密码'));

        return $form;
    }
}
