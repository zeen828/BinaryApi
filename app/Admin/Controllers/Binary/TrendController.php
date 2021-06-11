<?php

namespace App\Admin\Controllers\Binary;

use App\Models\Binary\BinaryCurrencyTrend;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TrendController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'BinaryCurrencyTrend';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new BinaryCurrencyTrend());

        $grid->column('id', __('field.id'));
        $grid->column('binary_currency_id', __('Binary currency id'));
        $grid->column('period', __('Period'));
        $grid->column('bet_at', __('Bet at'));
        $grid->column('stop_at', __('Stop at'));
        $grid->column('draw_at', __('Draw at'));
        $grid->column('draw', __('Draw'));
        $grid->column('trend_before', __('Trend before'));
        $grid->column('trend', __('Trend'));
        $grid->column('forecast', __('Forecast'));
        $grid->column('draw_rule_json', __('Draw rule json'));
        $grid->column('max', __('Max'));
        $grid->column('min', __('Min'));
        $grid->column('odd', __('Odd'));
        $grid->column('even', __('Even'));
        $grid->column('rise', __('Rise'));
        $grid->column('fall', __('Fall'));
        $grid->column('bet_quantity', __('Bet quantity'));
        $grid->column('bet_amount', __('Bet amount'));
        $grid->column('draw_quantity', __('Draw quantity'));
        $grid->column('draw_amount', __('Draw amount'));
        $grid->column('draw_rate', __('Draw rate'));
        $grid->column('redeem', __('Redeem'));
        $grid->column('status', __('field.status'));
        $grid->column('created_at', __('field.created_at'));
        $grid->column('updated_at', __('field.updated_at'));

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
        $show = new Show(BinaryCurrencyTrend::findOrFail($id));

        $show->field('id', __('field.id'));
        $show->field('binary_currency_id', __('Binary currency id'));
        $show->field('period', __('Period'));
        $show->field('bet_at', __('Bet at'));
        $show->field('stop_at', __('Stop at'));
        $show->field('draw_at', __('Draw at'));
        $show->field('draw', __('Draw'));
        $show->field('trend_before', __('Trend before'));
        $show->field('trend', __('Trend'));
        $show->field('forecast', __('Forecast'));
        $show->field('draw_rule_json', __('Draw rule json'));
        $show->field('max', __('Max'));
        $show->field('min', __('Min'));
        $show->field('odd', __('Odd'));
        $show->field('even', __('Even'));
        $show->field('rise', __('Rise'));
        $show->field('fall', __('Fall'));
        $show->field('bet_quantity', __('Bet quantity'));
        $show->field('bet_amount', __('Bet amount'));
        $show->field('draw_quantity', __('Draw quantity'));
        $show->field('draw_amount', __('Draw amount'));
        $show->field('draw_rate', __('Draw rate'));
        $show->field('redeem', __('Redeem'));
        $show->field('status', __('field.status'));
        $show->field('created_at', __('field.created_at'));
        $show->field('updated_at', __('field.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new BinaryCurrencyTrend());

        $form->number('binary_currency_id', __('Binary currency id'));
        $form->text('period', __('Period'));
        $form->datetime('bet_at', __('Bet at'))->default(date('Y-m-d H:i:s'));
        $form->datetime('stop_at', __('Stop at'))->default(date('Y-m-d H:i:s'));
        $form->datetime('draw_at', __('Draw at'))->default(date('Y-m-d H:i:s'));
        $form->text('draw', __('Draw'));
        $form->text('trend_before', __('Trend before'));
        $form->text('trend', __('Trend'));
        $form->textarea('forecast', __('Forecast'));
        $form->textarea('draw_rule_json', __('Draw rule json'));
        $form->number('max', __('Max'));
        $form->number('min', __('Min'));
        $form->number('odd', __('Odd'));
        $form->number('even', __('Even'));
        $form->number('rise', __('Rise'));
        $form->number('fall', __('Fall'));
        $form->number('bet_quantity', __('Bet quantity'));
        $form->decimal('bet_amount', __('Bet amount'))->default(0.000);
        $form->number('draw_quantity', __('Draw quantity'));
        $form->decimal('draw_amount', __('Draw amount'))->default(0.000);
        $form->decimal('draw_rate', __('Draw rate'))->default(0.00);
        $form->switch('redeem', __('Redeem'));
        $form->switch('status', __('field.status'));

        return $form;
    }
}
