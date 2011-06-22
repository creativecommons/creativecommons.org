from webob import Request

from cc.engine import util

util._activate_testing()


def test_jinja():
    """
    Test that our Jinja environment works by examining this friendly
    bunny field.
    """
    bunnies = [{'name': 'Lilian', 'description': 'lazy'},
               {'name': 'Tobias', 'description': 'furious'},
               {'name': 'Frank', 'description': 'grouch'}]
    fake_request = Request.blank('/bunnies/')
    result = util.render_template(
        fake_request, 'test/bunnies.html', {'bunnies': bunnies})
    assert 'Welcome to the bunny field!' in result
    assert 'Lilian the lazy' in result

    test_results = util.TEST_TEMPLATE_CONTEXT.pop('test/bunnies.html')
    assert test_results['bunnies'] is bunnies
    assert test_results['request'] is fake_request
