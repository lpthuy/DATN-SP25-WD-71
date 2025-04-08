import { View, Text, StyleSheet, Button, Alert } from 'react-native';
import { useLocalSearchParams, router } from 'expo-router';
import { useEffect, useState } from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';
import axios from 'axios';

export default function OrderDetailScreen() {
  const params = useLocalSearchParams();
  const [order, setOrder] = useState<any>(null);
  const [status, setStatus] = useState('');
  const [loading, setLoading] = useState(false);

  // ✅ Phân tích dữ liệu đơn hàng khi truyền từ màn danh sách
  useEffect(() => {
    if (params.order) {
      try {
        const parsed = typeof params.order === 'string' ? JSON.parse(params.order) : params.order;
        setOrder(parsed);
        setStatus(parsed.status);
      } catch (err) {
        console.log('❌ JSON parse lỗi:', err);
      }
    }
  }, [params.order]);

  const updateStatus = async () => {
    const token = await AsyncStorage.getItem('shipperToken');
    if (!token || !order) {
      Alert.alert('❌ Lỗi', 'Không có token hoặc đơn hàng không hợp lệ');
      return;
    }

    try {
      setLoading(true);

      const res = await axios.put(
        `http://192.168.100.179:8000/api/shipper/orders/${order.id}/status`,
        { status },
        {
          headers: {
            Authorization: `Bearer ${token}`,
            Accept: 'application/json',
          },
        }
      );

      // ✅ Kiểm tra phản hồi từ server
      if (res.data.status === 'success') {
        setStatus(res.data.order.status); // cập nhật lại trạng thái
        Alert.alert('✅ Thành công', 'Đơn hàng đã được cập nhật');

        // ✅ Trở lại màn danh sách để fetch lại danh sách mới
        router.replace('/screens/OrdersScreen');
      } else {
        Alert.alert('❌ Lỗi', res.data.message || 'Không thể cập nhật trạng thái');
      }
    } catch (err: any) {
      const msg = err.response?.data?.message || err.message;
      console.log('❌ Lỗi cập nhật:', msg);
      Alert.alert('❌ Lỗi', msg);
    } finally {
      setLoading(false);
    }
  };

  if (!order) {
    return (
      <View style={styles.container}>
        <Text style={styles.title}>Lỗi hiển thị đơn hàng</Text>
        <Text>Dữ liệu không hợp lệ hoặc đơn hàng bị thiếu.</Text>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Chi tiết đơn hàng</Text>
      <Text>Mã đơn: {order.order_code}</Text>
      <Text>Phương thức: {order.payment_method}</Text>
      <Text>Thanh toán: {order.is_paid ? 'Đã thanh toán' : 'Chưa thanh toán'}</Text>
      <Text>Trạng thái hiện tại: {translateStatus(status)}</Text>

      {status === 'shipping' ? (
        <View style={styles.buttonGroup}>
          <Button
            title="Hoàn thành đơn hàng"
            onPress={() => setStatus('completed')}
            disabled={loading}
          />
          <View style={{ marginTop: 10 }}>
            <Button
              title="Cập nhật trạng thái"
              onPress={updateStatus}
              disabled={loading}
            />
          </View>
        </View>
      ) : (
        <Text style={styles.disabledText}>Không thể cập nhật trạng thái khi đơn hàng đã hoàn tất.</Text>
      )}
    </View>
  );
}

const translateStatus = (status: string) => {
  switch (status) {
    case 'confirming': return 'Chờ xác nhận';
    case 'processing': return 'Đang xử lý';
    case 'shipping': return 'Đang giao hàng';
    case 'completed': return 'Đã giao thành công';
    case 'cancelled': return 'Đã huỷ';
    case 'returning': return 'Đã hoàn trả';
    default: return status;
  }
};

const styles = StyleSheet.create({
  container: { flex: 1, padding: 20 },
  title: { fontSize: 22, fontWeight: 'bold', marginBottom: 10 },
  buttonGroup: { marginTop: 20 },
  disabledText: {
    marginTop: 30,
    fontSize: 16,
    color: '#888',
  },
});
