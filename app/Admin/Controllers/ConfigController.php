<?php

namespace App\Admin\Controllers;

use App\Config;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ConfigController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '配置字段';

    /**
     * @see 获取类型
     * @return array
     */
    public function getType():array
    {
        return [
            1 => '网站设置',
            2 => '上传设置',
            3 => '商城费率设置'
        ];
    }

    /**
     * @see 获取文本类型
     * @return array
     */
    public function getTextType():array
    {
        return [
            'text' => '文本框',
            'select' => '下拉框',
            'fileupload' => '图片上传',
            'kindeditor' => '富文本编辑器',
            'password'  => '密码框'
        ];
    }
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Config);

        $grid->column('id', __('Id'));
        $grid->column('k', __('字段键值'));
        $grid->column('v', __('字段值'));
        $grid->column('type', __('所属配置'))->select($this->getType());
        $grid->column('name', __('字段名称'));
//        $grid->column('desc', __('字段文本框提示'));
        $grid->column('sort', __('排序'))->editable();
        $grid->column('text_type', __('文本类型'))->select($this->getTextType());
        //$grid->column('deleted_at', __('Deleted at'));
        $grid->column('created_at', __('创建日期'));
        //$grid->column('updated_at', __('Updated at'));
        $grid->filter(function ($filter){
            $filter->disableIdFilter();
            $filter->equal('type','所属配置')->select($this->getType());
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
        $show = new Show(Config::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('k', __('字段键值'));
        $show->field('v', __('字段值'));
        $show->field('type', __('所属配置'));
        $show->field('name', __('字段名称'));
//        $show->field('desc', __('字段文本框提示'));
        $show->field('sort', __('排序'));
        $show->field('text_type', __('文本类型'));
//        $show->field('deleted_at', __('Deleted at'));
//        $show->field('created_at', __('Created at'));
//        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Config);

        $form->text('k', __('字段键值'));
        $form->textarea('v', __('字段值'))->default('');
        $form->select('type',__('所属配置'))->options($this->getType())->default(1);
        $form->text('name', __('字段名称'))->default('');
//        $form->text('desc', __('字段文本框提示'))->default('');
        $form->select('text_type',__('文本类型'))->options($this->getTextType())->default(1);
        $form->number('sort', __('排序'))->default(0);
        $form->text('text_value', __('文本值'))->default('');


        return $form;
    }


    public function getKvbyTypeId(Content $content,int $id)
    {

        $form = $this->form2($id);
        $form->setAction(route('admin.postKvbyTypeId',['id'=>$id]));
        return $content
            ->title($this->getType()[$id])
            ->description($this->getType()[$id])
            ->body($form);
        return $form;
    }

    public function form2(int $id)
    {
        $aConfig = Config::select('id','k','name','desc','v','text_type','text_value')->where('type',$id)->get();
        $form = new Form(new Config);
        foreach ($aConfig as $key => $value) {
            $oForm = call_user_func_array([$form,$value['text_type']],[$value['k'],__($value['name'])])->default($value['v']);
            if (
                $value['text_type'] == 'select'
                && is_array(explode(',',$value['text_value']))
            ) {
                $oForm->options(explode(',',$value['text_value']));
            }
        }
        return $form;
    }


    public function postKvbyTypeId(int $id,Config $config)
    {
        $aParam = request()->input();
        foreach ($aParam as $key=>$value) {
            $config->where([['type',$id],['k',$key]])->update(['v' => $value ?? '']);
        }
        admin_toastr('修改配置成功！');
        return redirect(route('admin.getKvbyTypeId',['id'=> $id]));

    }

}
