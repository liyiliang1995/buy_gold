<?php

namespace App\Admin\Controllers;

use App\News;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class NewsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '文章管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new News);

        $grid->column('id', __('Id'));
        $grid->column('title', __('标题'));
        $grid->column('type', __('类型'));
        $grid->column('content', __('内容'));
        $grid->column('is_recommend', __('推荐'));
        //$grid->column('deleted_at', __('Deleted at'));
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('更新时间'));

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
        $show = new Show(News::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('标题'));
        $show->field('type', __('类型'));
        $show->field('content', __('内容'));
        $show->field('is_recommend', __('推荐'));
     //   $show->field('deleted_at', __('Deleted at'));
        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('更新时间'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new News);

        $form->text('title', __('标题'));
        $form->switch('type', __('类型'));
        $form->textarea('content', __('内容'));
        $form->switch('is_recommend', __('推荐'));

        return $form;
    }
}
