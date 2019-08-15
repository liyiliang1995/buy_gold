<?php

namespace App\Admin\Controllers;

use App\GoldFlow;
use App\Member;
use App\Logics\MemberLogic;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
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

        $grid->column('id', __('Id'))->sortable();
        $grid->column('name', __('姓名'));
        $grid->column('phone', __('手机号码'));
        $grid->column('phone2', __('联系手机号'));
        $grid->column('gold', __('金币'))->sortable();
        $grid->column('energy', __('能量'))->sortable();
        $grid->column('integral', __('积分'))->sortable();
        $grid->column('parent', __('上级用户'))->display(function (){
            return $this->parentuser->phone ?? "无";
        });
        $grid->column('child_user_num', __('代理下级个数'));
        $grid->column('wechat', __('微信'));
        $grid->column('rate', __('股东分成比列'));
        $grid->column('status', __('状态'))->display(function ($status) {
            if ($this->child_user_num  == 0) {
                return '待激活';
            } else {
                if ($status == 1) {
                    return '正常';
                } else {
                    return "冻结";
                }
            }
        });
        $grid->column('is_admin', __('是否股东'))->display(function ($is_admin) {
            if ($is_admin == 1) {
                return '是';
            } else {
                return '否';
            }
        });
//        $grid->column('ship_address.ship_address', __('收货地址'));
        $grid->column('created_at', __('创建时间'));
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableView();
            $url = route("admin.recharge", ['id' => $actions->getKey()]);
            $actions->append('<a href="' . $url . '">充值</a>');
            $url2 = route('goldflow.index',['user_id'=>$actions->getKey()]);
            $actions->append('<a href="' . $url2 . '"><span style="color:red"> 金币</span></a>');

            $url3 = route('energy-flows.index',['user_id'=>$actions->getKey()]);
            $actions->append('<a href="' . $url3 . '"><span style="color:red"> 能量</span></a>');

            $url4 = route('integral-flows.index',['user_id'=>$actions->getKey()]);
            $actions->append('<a href="' . $url4 . '"><span style="color:red"> 积分</span></a>');
        });
        $grid->model()->orderBy('id', 'desc');
        $grid->disableExport();
        $grid->disableRowSelector();
        // 去掉默认的id过滤器
        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('phone', '手机号码');
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
        $form->decimal('rate', __('股东金币分成比列(例如输入12为12%)'))->default(0.00);
        $form->text('wechat', __('微信'));
        $form->select('is_admin', __('是否股东'))->options([0 => '否', 1 => '是'])->default(0);
        $form->password('password', __('密码'));
        // 去掉`查看`checkbox
        $form->disableViewCheck();
        // 去掉`继续编辑`checkbox
        $form->disableEditingCheck();
        // 去掉`继续创建`checkbox
        $form->disableCreatingCheck();
        return $form;
    }

    public function form2(int $id)
    {
        $member = Member::find($id);
        $form   = new Form($member);
        $form->text('name', __('名称'))->default($member->name)->disable();
        $form->mobile('phone', __('手机号码'))->default($member->phone)->disable();
        $form->decimal('not', __('已有金额'))->default($member->gold)->disable();
        $form->decimal('gold', __('充值金额'))->default(0.00);
        return $form;
    }

    public function recharge(int $id, Content $content)
    {
        $form = $this->form2($id);
        $form->setAction(route('admin.post_recharge', ['id' => $id]));
        return $content
            ->title("会员充值")
            ->description("金币充值(正数增加负数扣除)")
            ->body($form);
        return $form;
    }

    /**
     * @param int $id
     * @param Member $member
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postRecharge(int $id, Member $member)
    {
        $aData['gold']      = (float)request()->input('gold');
        $aData['member_id'] = $id;
        $this->Logic($member)->recharge($aData);
        return redirect(route('members.index'));
    }

    /**
     * @param $oModel
     * @return MemberLogic
     */
    public function Logic($oModel)
    {
        return new MemberLogic($oModel);
    }

    /**
     * @param int $id
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|mixed|null|\Symfony\Component\HttpFoundation\Response
     */
    public function update($id)
    {
        $member = new Member();
        $fRate  = request()->input('rate');
        if ($fRate < 0) {
            throw new \Exception("分成比列不能小于0");
        }
        $is_admin = request()->input('is_admin');
        if ($is_admin) {
            $haverate = $member->where(['is_admin' => 1, ['id', '<>', $id]])->sum('rate');
            if (bcadd($haverate, $fRate, 5) > 100) {
                throw new \Exception("所有股东分成比例之和不能超过100");
            }
        }
        return $this->form()->update($id);
    }


}
