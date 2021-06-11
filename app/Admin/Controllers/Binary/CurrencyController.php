<?php

namespace App\Admin\Controllers\Binary;

use App\Models\Binary\BinaryCurrency;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CurrencyController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'BinaryCurrency';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new BinaryCurrency());

        $grid->column('id', __('field.id'));
        $grid->column('binary_id', __('Binary id'));
        $grid->column('binary_name', __('Binary name'));
        $grid->column('binary_code', __('Binary code'));
        $grid->column('currency_name', __('Currency name'));
        $grid->column('currency_code', __('Currency code'));
        $grid->column('trend_data_json', __('Trend data json'));
        $grid->column('trend_digits', __('Trend digits'));
        $grid->column('trend_repeat', __('Trend repeat'));
        $grid->column('forecast_data_json', __('Forecast data json'));
        $grid->column('forecast_digits', __('Forecast digits'));
        $grid->column('forecast_repeat', __('Forecast repeat'));
        $grid->column('week', __('Week'));
        $grid->column('start_t', __('Start t'));
        $grid->column('end_t', __('End t'));
        $grid->column('stop_enter', __('Stop enter'));
        $grid->column('repeat', __('Repeat'));
        $grid->column('reservation', __('Reservation'));
        $grid->column('win_rate', __('Win rate'));
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
        $show = new Show(BinaryCurrency::findOrFail($id));

        $show->field('id', __('field.id'));
        $show->field('binary_id', __('Binary id'));
        $show->field('binary_name', __('Binary name'));
        $show->field('binary_code', __('Binary code'));
        $show->field('currency_name', __('Currency name'));
        $show->field('currency_code', __('Currency code'));
        $show->field('trend_data_json', __('Trend data json'));
        $show->field('trend_digits', __('Trend digits'));
        $show->field('trend_repeat', __('Trend repeat'));
        $show->field('forecast_data_json', __('Forecast data json'));
        $show->field('forecast_digits', __('Forecast digits'));
        $show->field('forecast_repeat', __('Forecast repeat'));
        $show->field('week', __('Week'));
        $show->field('start_t', __('Start t'));
        $show->field('end_t', __('End t'));
        $show->field('stop_enter', __('Stop enter'));
        $show->field('repeat', __('Repeat'));
        $show->field('reservation', __('Reservation'));
        $show->field('win_rate', __('Win rate'));
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
        $form = new Form(new BinaryCurrency());

        $form->number('binary_id', __('Binary id'));
        $form->text('binary_name', __('Binary name'));
        $form->text('binary_code', __('Binary code'));
        $form->text('currency_name', __('Currency name'));
        $form->text('currency_code', __('Currency code'));
        $form->textarea('trend_data_json', __('Trend data json'));
        $form->switch('trend_digits', __('Trend digits'));
        $form->switch('trend_repeat', __('Trend repeat'));
        $form->textarea('forecast_data_json', __('Forecast data json'));
        $form->switch('forecast_digits', __('Forecast digits'));
        $form->switch('forecast_repeat', __('Forecast repeat'));
        $form->text('week', __('Week'))->default('[]');
        $form->time('start_t', __('Start t'))->default(date('H:i:s'));
        $form->time('end_t', __('End t'))->default(date('H:i:s'));
        $form->number('stop_enter', __('Stop enter'))->default(60);
        $form->number('repeat', __('Repeat'));
        $form->switch('reservation', __('Reservation'))->default(1);
        $form->decimal('win_rate', __('Win rate'))->default(0.40);
        $form->switch('status', __('field.status'));

        return $form;
    }
}
