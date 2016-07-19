$.confirm.defaults = {
	// 样式
	css: "http://static.qianduanblog.com/css/jquery.confirm/default.min.css?v=" + Math.ceil(new Date() / 86400000),
	// 确认框内容
	content: "确认吗？",
	// 确认按钮文字
	sureButton: "确认",
	// 取消按钮文字
	cancelButton: "取消",
	// 位置
	position: {},
	// 自动打开
	autoOpen: false,
	// 动画持续时间
	duration: 123,
	// 打开确认框回调
	onopen: emptyFn,
	// 单击了确认或者取消回调
	onclick: emptyFn,
	// 确认回调
	onsure: emptyFn,
	// 取消回调
	oncancel: emptyFn,
	// 关闭确认框回调
	onclose: emptyFn
}