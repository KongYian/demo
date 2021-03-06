<?php

namespace App\Admin\Controllers;

use App\Models\AdminMember;
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
    protected $title = 'App\Models\AdminMember';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AdminMember);



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
        $show = new Show(AdminMember::findOrFail($id));



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new AdminMember);



        return $form;
    }

    public function index(Content $content)
    {
        return $content
            ->title('会员信息')
            ->description('列表')
            ->body($this->grid());
    }

    public function create(Content $content)
    {
        return parent::create($content); // TODO: Change the autogenerated stub
    }

    public function store()
    {

    }

    public function edit($id, Content $content)
    {
        return parent::edit($id, $content); // TODO: Change the autogenerated stub
    }

    public function update($id)
    {
    }

    public function destroy($id)
    {
    }
}
